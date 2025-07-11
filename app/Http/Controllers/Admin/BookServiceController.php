<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromoCode;
use App\Models\BookService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\BookedServiceStatusUpdated;
use App\Events\BookServicePdfUploaded;
use App\Models\BookedServicePdf;
use App\Models\Transaction;
use App\Models\User;

class BookServiceController extends Controller
{
    private $bookService;
    private $promoCode;
    private $transaction;
    private $stripe;
    private $bookedservicepdf;
    private $user;
    public function __construct(BookService $bookService, PromoCode $promoCode, Transaction $transaction, BookedServicePdf $bookedservicepdf, User $user)
    {
        $this->bookService = $bookService;
        $this->promoCode = $promoCode;
        $this->transaction = $transaction;
        $this->bookedservicepdf = $bookedservicepdf;
        $this->user = $user;
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }
    public function edit($id = null)
    {
        // return $id;
        $bookedService = $this->bookService->with(['service', 'transactions'])->find($id);
        $nextStatus = $bookedService->status + 1;
        $statusLabel = BookService::$status_array[$nextStatus] ?? null;
        $statusText = $statusLabel ? 'Make ' . $statusLabel : null;
        $dataIdText = $statusLabel ? $statusLabel : null;
        $bookedService->nextStatus = $nextStatus;
        $bookedService->statusLabel = $statusLabel;
        $bookedService->statusText = $statusText;
        $bookedService->dataIdText = $dataIdText;
        $promo_codes = $this->promoCode->get();
        if ($bookedService) {
            return view("admin.booked-services.edit", compact("bookedService", "promo_codes"));
        } else {
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $bookedService = $this->bookService->with('user')->find($id);
        if ($bookedService) {
            $user_id = $bookedService->user_id;
            $fields = $request->fields;
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $bookField = $bookedService->bookFields()->where("field_id", $key)->first();
                    if ($bookField) {
                        $bookField->update(["value" => $value]);
                    }
                }
            }
            return redirect(route("users.edit", $user_id))->with("success", "Service updated Successfully!");
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }

    public function destroy($id)
    {
        $bookedService = $this->bookService->find($id);
        if ($bookedService) {
            $bookedService->bookFields()->delete();
            $bookedService->delete();
            return back()->with("success", "Service deleted Successfully!");
        } else {
            return back()->with("error", "Something went Wrong!");
        }
    }
    public function datatable(Request $request)
    {
        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->bookService;
            $services = $this->bookService;

            if (!empty($search)) {
                $services = $services->datatableSearch($search);
            }
            $totalRecordswithFilter = clone $services;
            $services->orderBy('id', 'ASC');

            /*Set limit offset */
            $services = $services->offset(intval($data['start']));
            $services = $services->limit(intval($data['length']));

            $services = $services->get();
            foreach ($services as $k => $val) {
                $services[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->getUser() . ' (' . $val->user->email . ')</a>' : '-';
                $services[$k]['membership_id'] = $val->user ? $val->user->membership_id : '-/-';
                $services[$k]['service'] = $val->getService();
                $services[$k]['status_view'] = service_book_status($val->status);
                $services[$k]['action'] = view('admin.booked-services.actions')->with('service', $val)->render();
                $services[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $services,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $bookedService = $this->bookService->find($id);
        if ($bookedService) {
            $fields = $bookedService->bookFields()->get();
            $view = view("admin.packages.fields", compact("fields", "bookedService"))->render();
            $title = strtoupper($bookedService->getPackage()) . '-' . strtoupper($bookedService->getService());
            $response = [
                "status" => true,
                "body" => $view,
                "title" => $title
            ];
        } else {
            $response = [
                "status" => false,
                "message" => "Something went wrong!"
            ];
        }
        return $response;
    }

    public function createInvoice(Request $request)
    {
        try {
            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
                'amount'            => 'required|numeric|min:0',
                'final_price'       => 'required|numeric|min:0',
                // 'promo_code_id'         => 'nullable|exists:promo_codes,id',
            ]);
            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);
            $serviceName = $bookedService->service->name ?? '-';
            // Load Coupon name if provided
            // $promoCode = $this->promoCode->find($request->promo_code_id);
            // $promoName = $promoCode->name ?? '-';
            $product = $this->stripe->products->create([
                'name' => $serviceName,
            ]);
            // Create a price
            $price = $this->stripe->prices->create([
                'unit_amount' => $request->final_price * 100, // Amount in cents (i.e. $50.00)
                'currency' => 'gbp',
                'product' => $product->id,
            ]);
            // Create a payment link
            $paymentLink = $this->stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
            ]);
            //  Save link to invoice url column
            $bookedService->invoice_url = $paymentLink->url;
            $bookedService->invoice_status = 1;
            $bookedService->status = 5;
            $bookedService->save();
            $bookedService->total_amount = $request->amount;
            $bookedService->discount_amount = $request->amount - $request->final_price;
            $bookedService->payable_amount = $request->final_price;
            $bookedService->service_name = $bookedService->getService();
            event(new BookedServiceStatusUpdated($bookedService));
            $this->transaction->create([
                "user_id" => $bookedService->user_id,
                "book_service_id" => $request->book_service_id,
                "session_id" => $paymentLink->id,
                "promo_id" => $request->promo_code_id ? $request->promo_code_id : "",
                "total_amount" => $request->amount,
                "discount_amount" => $request->amount -  $request->final_price,
                "payable_amount" => $request->final_price,
                "transaction_type" => "invoice",
                "invoice_url" => $paymentLink->url,
                "status" => "0",
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully.',
                'url'     => $paymentLink->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function requestDeposit(Request $request)
    {
        try {
            $deposit_amount = 100; // fixed in pound
            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
            ]);
            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);
            // Create a product (if not already created)
            $product = $this->stripe->products->create([
                'name' => 'Deposit Payment',
            ]);
            // Create a price
            $price = $this->stripe->prices->create([
                'unit_amount' => $deposit_amount * 100, // Amount in cents (i.e. $50.00)
                'currency' => 'gbp',
                'product' => $product->id,
            ]);
            // Create a payment link
            // $paymentLink = PaymentLink::create([
            $paymentLink = $this->stripe->paymentLinks->create([
                'line_items' => [
                    [
                        'price' => $price->id,
                        'quantity' => 1,
                    ],
                ],
            ]);
            $bookedService->deposit_url = $paymentLink->url;
            $bookedService->deposit_status = 1;
            $bookedService->status = 2;
            $bookedService->save();
            $this->transaction->create([
                "user_id" => $bookedService->user_id,
                "book_service_id" => $request->book_service_id,
                "session_id" => $paymentLink->id,
                "promo_id" => "",
                "total_amount" => $deposit_amount,
                "discount_amount" => 0,
                "payable_amount" => $deposit_amount,
                "transaction_type" => "deposit",
                "invoice_url" => $paymentLink->url,
                "status" => "0",
            ]);
            $bookedService->total_amount = $deposit_amount;
            $bookedService->discount_amount = 0;
            $bookedService->payable_amount = $deposit_amount;
            $bookedService->service_name = $bookedService->getService();
            event(new BookedServiceStatusUpdated($bookedService));
            return response()->json([
                'success' => true,
                'message' => 'Deposit requested successfully.',
                'url'     => $paymentLink->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadSchedule(Request $request)
    {

        try {
            $data = $request->validate([
                'book_service_id' => 'required|exists:book_services,id',
                'pdf_file' => 'nullable|file|mimes:pdf|max:15120', // 5MB
            ]);
            // Load BookService with its Service
            $bookedService = $this->bookService->with('service')->find($request->book_service_id);
            $bookedService->status = $request->status;
            $filePath = null;
            // if ($request->hasFile('pdf_file')) {
            //     $file = $request->file('pdf_file');
            //     // Generate unique filename: timestamp + original extension
            //     $fileName = time() . '.' . $file->getClientOriginalExtension();
            //     // Store file in 'storage/app/public/pdf_uploads' and get full path
            //     $filePath = $file->storeAs('pdf_uploads', $fileName, 'public');

            //     $bookedService->schedule_created = true;
            //     $bookedService->schedule_pdf = $filePath ?? null;
            // }

            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');

                // Generate unique filename
                $fileName = time() . '.' . $file->getClientOriginalExtension();

                // Move file to public/assets/pdfs directory
                $file->move(public_path('assets/pdfs'), $fileName);

                // Save the path (relative to public) in DB
                $bookedService->schedule_created = true;
                $bookedService->schedule_pdf = 'assets/pdfs/' . $fileName;
            }

            $bookedService->save();
            $bookedService->transaction = $this->transaction->where('book_service_id', $bookedService->id)->first();
            event(new BookedServiceStatusUpdated($bookedService));
            return response()->json([
                'success' => true,
                'message' => $request->status_text . ' action done successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function viewBookedServices()
    {
        return view('admin.dashboard.all_book_service');
    }

    public function dashBookServiceDatatable(Request $request)
    {
        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->bookService;
            $services = $this->bookService->with('user', 'package');

            if (!empty($search)) {
                $services = $services->datatableSearch($search);
            }
            $totalRecordswithFilter = clone $services;
            $services->orderBy('id', 'ASC');

            /*Set limit offset */
            $services = $services->offset(intval($data['start']));
            $services = $services->limit(intval($data['length']));

            $services = $services->get();
            foreach ($services as $k => $val) {
                $services[$k]['customer_name'] = $val->user ? '<a href="' . route("users.edit", $val->user->id) . '">' . $val->getUser() . ' (' . $val->user->email . ')</a>' : '-';
                $services[$k]['membership_id'] = $val->user ? $val->user->membership_id : '-/-';

                $services[$k]['service'] = $val->getService();
                $services[$k]['package'] = $val->getPackage() ?? '';

                $services[$k]['status_view'] = service_book_status($val->status);
                // $services[$k]['status_view'] = service_book_status($val->status);
                $services[$k]['action'] = view('admin.booked-services.actions')->with('service', $val)->render();
                $services[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $services,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function uploadBookServicePDF(Request $request)
    {
        $request->validate([
            'pdf_id' => 'nullable|integer|exists:booked_service_pdfs,id',
            'booked_service_id' => 'required|exists:book_services,id',
            'subject' => 'required|string|max:255',
            'text' => 'required|string',
            'file' => 'nullable|file|mimes:pdf|max:10240', // File is optional for update
        ]);

        $filename = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/pdfs'), $filename);
        }

        // Preserve old file if no new one uploaded during update
        if ($request->filled('pdf_id') && !$filename) {
            $existing = $this->bookedservicepdf->find($request->pdf_id);
            if ($existing) {
                $filename = basename($existing->file);
            }
        }

        $this->bookedservicepdf->updateOrCreate(
            ['id' => $request->pdf_id],
            [
                'book_service_id' => $request->booked_service_id,
                'subject' => $request->subject,
                'text' => $request->text,
                'file' => 'assets/pdfs/' . $filename,
            ]
        );

        return response()->json(['success' => true, 'message' => 'PDF saved successfully.']);
    }


    public function bookServicePDFDatatable(Request $request)
    {
        try {
            $data = $request->all();
            $search = @$data['search']['value'];
            $iTotalRecords = $this->bookedservicepdf;
            $services = $this->bookedservicepdf;

            if (!empty($search)) {
                $services = $services->datatableSearch($search);
            }
            $totalRecordswithFilter = clone $services;
            $services->orderBy('id', 'ASC');

            /*Set limit offset */
            $services = $services->offset(intval($data['start']));
            $services = $services->limit(intval($data['length']));

            $services = $services->get();
            foreach ($services as $k => $val) {

                $services[$k]['subject'] = $val->subject ?? '';
                $services[$k]['text'] = $val->text ?? '';

                $services[$k]['action'] = view('admin.booked-services.pdfactions')->with('service', $val)->render();
                $services[$k] = $val;
            }

            return response()->json([
                'draw' => intval($data['draw']),
                'iTotalRecords' => $iTotalRecords->count(),
                'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
                'aaData' => $services,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroyBookServicePDF($id)
    {
        $servicepdf = $this->bookedservicepdf->find($id);

        if ($servicepdf) {
            // Delete file from public/assets/pdfs if exists
            $filePath = public_path($servicepdf->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $servicepdf->delete();
            return back()->with("success", "PDF deleted successfully!");
        } else {
            return back()->with("error", "Something went wrong!");
        }
    }


    public function sendBookServicePDFEmail(Request $request)
    {
        try {
            $request->validate([
                'pdf_id' => 'required|exists:booked_service_pdfs,id',
                'book_service_id' => 'required|exists:book_services,id',
                'user_id' => 'required|exists:users,id',
            ]);



            $bookedServicePdf = $this->bookedservicepdf->findOrFail($request->pdf_id);

            $user = $this->user->findOrFail($request->user_id); // Adjust if you're using a different User model

            $pdfPath = public_path($bookedServicePdf->file);

            $bookedServicePdf->user = $user;

            // if (!file_exists($pdfPath)) {
            //     return response()->json(['message' => 'PDF file not found.'], 404);
            // }

            // dd($bookedServicePdf);

            // Send email
            event(new BookServicePdfUploaded($bookedServicePdf));

            // return response()->json(['success' => true, 'message' => 'PDF email sent successfully.']);
            return response()->json([
                'success' => true,
                'message' => 'PDF email sent successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send PDF email.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
