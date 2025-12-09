 <!-- Verification Modal -->
 <div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="verificationModalLabel">Complete Verification</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div id="unverified-content">
                     <div class="text-center mb-3">
                         <p class="h5"><b>Why We Ask for Photo ID</b></p>
                         <p class="mb-2">
                             To ensure your safety and to comply with security
                             requirements in Pakistan, Pak Connections requires all
                             members to upload a valid Photo ID (Passport and Proof of Address).
                         </p>

                         <div id="additional-info" style="display: none;">
                             <p class="mb-2">
                                 This helps us verify your identity in case of emergencies and
                                 allows us to provide secure, reliable assistance.
                                 Please note: Your document may be approved or rejected based
                                 on clarity, validity, or compliance with our verification
                                 standards.
                             </p>
                             <p class="mb-0">
                                 <strong>Proof of Address requirement:</strong> Only Utility bills or Bank statement
                                 dated
                                 within the last 3 months are accepted.
                             </p>
                         </div>

                         <a href="javascript:void(0)" id="read-more-toggle" class="text-primary"
                             style="font-size: 14px; cursor: pointer;">
                             <span id="read-more-text">Read More</span>
                             <i class="fa fa-chevron-down" id="read-more-icon"></i>
                         </a>
                     </div>
                     <form id="verification-form" enctype="multipart/form-data">
                         @csrf
                         <!-- Passport Section -->
                         <div class="form-group">
                             <label for="passport_image">Passport <span class="text-danger"
                                     id="passport-required">*</span></label>

                             <!-- Verified Status for Passport -->
                             <div id="passport-verified-status" style="display: none;">
                                 <div class="alert alert-success d-flex align-items-center" role="alert">
                                     <i class="fa fa-check-circle mr-2"></i>
                                     <span>Passport has been verified and approved.</span>
                                 </div>
                             </div>

                             <!-- Pending Status for Passport -->
                             <div id="passport-pending-status" style="display: none;">
                                 <div class="alert alert-warning d-flex align-items-center" role="alert">
                                     <i class="fa fa-clock mr-2"></i>
                                     <span>Passport is pending admin review.</span>
                                 </div>
                             </div>

                             <!-- Rejected Status for Passport -->
                             <div id="passport-rejected-status" style="display: none;">
                                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                                     <i class="fa fa-times-circle mr-2"></i>
                                     <span>Passport was rejected. Please upload a new document.</span>
                                 </div>
                             </div>

                             <!-- File Input for Passport -->
                             <div id="passport-upload-section">
                                 <input type="file" class="form-control pdf_file passport-file" id="passport_image"
                                     name="passport_image" accept="image/*,application/pdf">
                                 <small class="form-text text-muted">Upload Passport (JPEG, PNG, JPG,
                                     PDF - Max: 5MB)</small>
                             </div>

                             <!-- Preview Container for Passport Images -->
                             <div class="form-group" id="passport-image-preview-container" style="display: none;">
                                 <label>Passport Preview:</label>
                                 <div class="text-center">
                                     <img id="passport-image-preview" src="" alt="Passport Preview"
                                         style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                 </div>
                             </div>

                             <!-- Preview Container for Passport PDFs -->
                             <div class="form-group" id="passport-pdf-preview-container" style="display: none;">
                                 <label>Passport Preview:</label>
                                 <div class="text-center">
                                     <iframe id="passport-pdf-preview" width="100%" height="500"
                                         style="border: 1px solid #ccc; border-radius: 5px;">
                                     </iframe>
                                 </div>
                             </div>
                         </div>

                         <!-- Proof of Address Section -->
                         <div class="form-group">
                             <label for="proof_of_address">Proof of Address <span class="text-danger"
                                     id="address-required">*</span></label>

                             <!-- Verified Status for Proof of Address -->
                             <div id="address-verified-status" style="display: none;">
                                 <div class="alert alert-success d-flex align-items-center" role="alert">
                                     <i class="fa fa-check-circle mr-2"></i>
                                     <span>Proof of Address has been verified and approved.</span>
                                 </div>
                             </div>

                             <!-- Pending Status for Proof of Address -->
                             <div id="address-pending-status" style="display: none;">
                                 <div class="alert alert-warning d-flex align-items-center" role="alert">
                                     <i class="fa fa-clock mr-2"></i>
                                     <span>Proof of Address is pending admin review.</span>
                                 </div>
                             </div>

                             <!-- Rejected Status for Proof of Address -->
                             <div id="address-rejected-status" style="display: none;">
                                 <div class="alert alert-danger d-flex align-items-center" role="alert">
                                     <i class="fa fa-times-circle mr-2"></i>
                                     <span>Proof of Address was rejected. Please upload a new document.</span>
                                 </div>
                             </div>

                             <!-- File Input for Proof of Address -->
                             <div id="address-upload-section">
                                 <input type="file" class="form-control pdf_file address-file" id="proof_of_address"
                                     name="proof_of_address" accept="image/*,application/pdf">
                                 <small class="form-text text-muted">
                                     Upload Proof of Address (JPEG, PNG, JPG, PDF - Max: 5MB)
                                 </small>
                                 <small class="form-text text-info d-block mt-1">
                                     <i class="fa fa-info-circle"></i> <strong>Accepted documents:</strong> Only
                                     Utility bills or Bank statement dated within the last 3 months are accepted.
                                 </small>
                             </div>

                             <!-- Preview Container for Proof of Address Images -->
                             <div class="form-group" id="address-image-preview-container" style="display: none;">
                                 <label>Proof of Address Preview:</label>
                                 <div class="text-center">
                                     <img id="address-image-preview" src="" alt="Proof of Address Preview"
                                         style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                 </div>
                             </div>

                             <!-- Preview Container for Proof of Address PDFs -->
                             <div class="form-group" id="address-pdf-preview-container" style="display: none;">
                                 <label>Proof of Address Preview:</label>
                                 <div class="text-center">
                                     <iframe id="address-pdf-preview" width="100%" height="500"
                                         style="border: 1px solid #ccc; border-radius: 5px;">
                                     </iframe>
                                 </div>
                             </div>
                         </div>
                         <div class="text-center">
                             <button type="submit" class="btn btn-primary" id="submit-verification-btn">
                                 <i class="fa fa-upload"></i> Submit Verification
                             </button>
                         </div>
                     </form>
                 </div>
                 <div id="pending-content" style="display: none;">
                     <div class="text-center">
                         <i class="fa fa-clock fa-5x text-warning mb-3"></i>
                         <h4>Verification Pending</h4>
                         <p>Your verification documents have been submitted <br> and are currently under review by our
                             admin
                             team.</p>
                         <p class="text-muted">You will be notified once your verification is complete.</p>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
