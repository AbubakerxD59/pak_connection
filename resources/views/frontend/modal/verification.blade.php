 <!-- Verification Modal -->
 <div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="verificationModalLabel">Complete Verification</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div id="unverified-content">
                     <p class="text-center mb-3">Please upload your passport image to complete
                         <br>the verification process.
                     </p>
                     <form id="verification-form" enctype="multipart/form-data">
                         @csrf
                         <div class="form-group">
                             <label for="passport_image">Passport Image <span class="text-danger">*</span></label>
                             <input type="file" class="form-control" id="passport_image" name="passport_image"
                                 accept="image/*,application/pdf" required>
                             <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, PDF (Max:
                                 5MB)</small>
                         </div>
                         <div class="form-group" id="image-preview-container" style="display: none;">
                             <label>Preview:</label>
                             <div class="text-center">
                                 <img id="image-preview" src="" alt="Preview"
                                     style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
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
