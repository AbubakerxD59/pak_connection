<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function checkStatus()
    {
        $user = User::find(auth()->user()->id);

        // Get latest document statuses
        $passportDocument = $user->verificationDocuments()
            ->where('document_type', 'passport')
            ->latest()
            ->first();
        
        $proofOfAddressDocument = $user->verificationDocuments()
            ->where('document_type', 'proof_of_address')
            ->latest()
            ->first();

        return response()->json([
            'status' => $user->verification_status,
            'is_verified' => $user->isVerified(),
            'is_pending' => $user->isVerificationPending(),
            'is_unverified' => $user->isUnverified(),
            'documents' => [
                'passport' => [
                    'status' => $passportDocument ? $passportDocument->status : 'unverified',
                    'is_approved' => $passportDocument && $passportDocument->status === 'approved',
                    'is_pending' => $passportDocument && $passportDocument->status === 'pending',
                    'is_rejected' => $passportDocument && $passportDocument->status === 'rejected',
                ],
                'proof_of_address' => [
                    'status' => $proofOfAddressDocument ? $proofOfAddressDocument->status : 'unverified',
                    'is_approved' => $proofOfAddressDocument && $proofOfAddressDocument->status === 'approved',
                    'is_pending' => $proofOfAddressDocument && $proofOfAddressDocument->status === 'pending',
                    'is_rejected' => $proofOfAddressDocument && $proofOfAddressDocument->status === 'rejected',
                ],
            ],
        ]);
    }

    public function upload(Request $request)
    {
        $user = User::find(auth()->user()->id);

        // Check existing document statuses
        $passportDocument = $user->verificationDocuments()
            ->where('document_type', 'passport')
            ->latest()
            ->first();
        
        $proofOfAddressDocument = $user->verificationDocuments()
            ->where('document_type', 'proof_of_address')
            ->latest()
            ->first();

        $passportApproved = $passportDocument && $passportDocument->status === 'approved';
        $proofOfAddressApproved = $proofOfAddressDocument && $proofOfAddressDocument->status === 'approved';

        // Build validation rules - only require files that aren't already approved
        $rules = [];
        if (!$passportApproved) {
            $rules['passport_image'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:5120';
        } else {
            $rules['passport_image'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        }

        if (!$proofOfAddressApproved) {
            $rules['proof_of_address'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:5120';
        } else {
            $rules['proof_of_address'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        }

        $request->validate($rules);

        $uploadedDocuments = [];
        $messages = [];

        // Upload passport file if provided and not already approved
        if ($request->hasFile('passport_image') && !$passportApproved) {
            $passportFileName = saveImage($request->file('passport_image'));

            // Create verification document record for passport
            VerificationDocument::create([
                'user_id' => $user->id,
                'document_type' => 'passport',
                'document_path' => $passportFileName,
                'status' => 'pending',
            ]);
            $uploadedDocuments[] = 'passport';
            $messages[] = 'Passport';
        }

        // Upload proof of address file if provided and not already approved
        if ($request->hasFile('proof_of_address') && !$proofOfAddressApproved) {
            $addressFileName = saveImage($request->file('proof_of_address'));

            // Create verification document record for proof of address
            VerificationDocument::create([
                'user_id' => $user->id,
                'document_type' => 'proof_of_address',
                'document_path' => $addressFileName,
                'status' => 'pending',
            ]);
            $uploadedDocuments[] = 'proof of address';
            $messages[] = 'Proof of Address';
        }

        // Update user verification status if at least one document was uploaded
        if (count($uploadedDocuments) > 0) {
            // Check if user should be set to pending (if they have pending documents)
            $hasPendingDocuments = $user->verificationDocuments()
                ->whereIn('status', ['pending'])
                ->exists();
            
            if ($hasPendingDocuments && $user->verification_status !== 'verified') {
                $user->update(['verification_status' => 'pending']);
            }

            $message = implode(' and ', $messages) . ' uploaded successfully.';
            if (count($uploadedDocuments) === 1) {
                $message .= ' Your verification is now pending admin approval.';
            } else {
                $message .= ' Your verification is now pending admin approval.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No new documents to upload. Please upload the required document(s).',
        ], 400);
    }
}
