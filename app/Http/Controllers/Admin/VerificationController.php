<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerificationApprovedMail;
use App\Mail\VerificationRejectedMail;
use App\Models\VerificationDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class VerificationController extends Controller
{
    public function __construct()
    {
        // Add permissions if you have them
        $this->middleware('permission:view_verification', ['only' => ['index']]);
        $this->middleware('permission:edit_verification', ['only' => ['edit', 'update']]);
        $this->middleware('permission:approve_verification', ['only' => ['approve']]);
        $this->middleware('permission:reject_verification', ['only' => ['reject']]);
    }
    public function index()
    {
        return view('admin.verification.index');
    }

    public function dataTable(Request $request)
    {
        $data = $request->all();
        $search = @$data['search']['value'];
        $iTotalRecords = VerificationDocument::count();
        $documents = VerificationDocument::with('user', "verifiedBy")->latest();

        if (!empty($search)) {
            $documents = $documents->search($search);
        }

        $totalRecordswithFilter = clone $documents;
        $documents->orderBy('id', 'ASC');

        /*Set limit offset */
        $documents = $documents->offset(intval($data['start']));
        $documents = $documents->limit(intval($data['length']));

        $documents = $documents->get();

        $documents->append(["user_details", "status_badge", "action", "created_date", "document"]);
        return response()->json([
            'draw' => intval($data['draw']),
            'iTotalRecords' => $iTotalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter->count(),
            'aaData' => $documents,
        ]);
    }

    public function viewUserDocuments($userId)
    {
        $user = User::with('verificationDocuments')->findOrFail($userId);
        $documents = $user->verificationDocuments->sortByDesc('created_at');

        return view('admin.verification.view-documents', compact('user', 'documents'));
    }

    public function approve(Request $request, $id)
    {
        $document = VerificationDocument::findOrFail($id);
        $user = $document->user;

        $document->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Check if both passport and proof of address are approved
        $passportApproved = $user->verificationDocuments()
            ->where('document_type', 'passport')
            ->where('status', 'approved')
            ->exists();

        $proofOfAddressApproved = $user->verificationDocuments()
            ->where('document_type', 'proof_of_address')
            ->where('status', 'approved')
            ->exists();

        // Update user verification status only if both documents are approved
        $bothVerified = false;
        if ($passportApproved && $proofOfAddressApproved) {
            $user->update(['verification_status' => 'verified']);
            $bothVerified = true;

            // Send approval email to member only when both documents are verified
            try {
                Mail::to($user->email)->send(new VerificationApprovedMail($user, $document));
            } catch (\Exception $e) {
                // Log error but don't fail the approval process
                Log::error('Failed to send verification approval email: ' . $e->getMessage());
            }
        }

        $message = 'Document approved successfully';
        if ($bothVerified) {
            $message .= '. Both documents are now approved and the member is verified. Notification email sent to member.';
        } else {
            $message .= '. Please approve the remaining document to complete verification.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $document = VerificationDocument::findOrFail($id);
        $user = $document->user;

        $document->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update user verification status
        $user->update(['verification_status' => 'rejected']);

        // Send rejection email to member
        try {
            Mail::to($user->email)->send(new VerificationRejectedMail($user, $document, $request->admin_notes));
        } catch (\Exception $e) {
            // Log error but don't fail the rejection process
            Log::error('Failed to send verification rejection email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Document rejected successfully and notification email sent to member.',
        ]);
    }
}
