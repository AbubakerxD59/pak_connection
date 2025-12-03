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

        return response()->json([
            'status' => $user->verification_status,
            'is_verified' => $user->isVerified(),
            'is_pending' => $user->isVerificationPending(),
            'is_unverified' => $user->isUnverified(),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'passport_image' => 'required|max:5120', // 5MB max
        ]);

        $user = User::find(auth()->user()->id);

        // Upload file
        if ($request->hasFile('passport_image')) {
            $fileName = saveImage($request->file('passport_image'));

            // Create verification document record
            VerificationDocument::create([
                'user_id' => $user->id,
                'document_type' => 'passport',
                'document_path' => $fileName,
                'status' => 'pending',
            ]);

            // Update user verification status
            $user->update(['verification_status' => 'pending']);

            return response()->json([
                'success' => true,
                'message' => 'Passport uploaded successfully. Your verification is now pending admin approval.',
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to upload passport image.',
        ], 400);
    }
}
