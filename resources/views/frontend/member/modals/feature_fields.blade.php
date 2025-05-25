<div class="modal fade" id="feature_fields" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="submitFeatureForm">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">
            <input type="hidden" id="service_id" name="service_id">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
