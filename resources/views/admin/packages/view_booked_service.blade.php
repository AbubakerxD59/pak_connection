<div class="modal fade" id="view_booked_service" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Service</h4>
                <button type="button" class="close view-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default view-modal-close" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $('.view-modal-close').on('click', function() {
        $('#view_booked_service').modal('hide');
    });
</script>
