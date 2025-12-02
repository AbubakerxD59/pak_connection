 <!-- Reject Modal -->
 <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="rejectModalLabel">Reject Verification Document</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <form id="reject-form">
                 <div class="modal-body">
                     <div class="form-group">
                         <label for="admin_notes">Reason for Rejection <span class="text-danger">*</span></label>
                         <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" required></textarea>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                     <button type="submit" class="btn btn-danger">Reject Document</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
