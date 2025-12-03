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
                             members to upload a valid Photo ID (Passport or NICOP/
                             NADRA Card).
                         </p>

                         <div id="additional-info" style="display: none;">
                             <p class="mb-0">
                                 This helps us verify your identity in case of emergencies and
                                 allows us to provide secure, reliable assistance.
                                 Please note: Your document may be approved or rejected based
                                 on clarity, validity, or compliance with our verification
                                 standards.
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
                        <div class="form-group">
                            <label for="passport_image">Photo ID / Document <span class="text-danger">*</span></label>
                            <input type="file" class="form-control pdf_file" id="passport_image"
                                name="passport_image" accept="image/*,application/pdf" required>
                            <small class="form-text text-muted">Upload Passport, NICOP, or NADRA Card (JPEG, PNG, JPG,
                                PDF - Max: 5MB)</small>
                        </div>
                        
                        <!-- Preview Container for Images -->
                        <div class="form-group" id="image-preview-container" style="display: none;">
                            <label>Preview:</label>
                            <div class="text-center">
                                <img id="image-preview" src="" alt="Preview"
                                    style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                            </div>
                        </div>
                        
                        <!-- Preview Container for PDFs -->
                        <div class="form-group" id="pdf-preview-container" style="display: none;">
                            <label>Preview:</label>
                            <div class="text-center">
                                <iframe id="pdf-preview" width="100%" height="500" style="border: 1px solid #ccc; border-radius: 5px;">
                                </iframe>
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
