<div class="modal fade" id="add_fields_modal" style="padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="fieldForm">
            @csrf
            <input type="hidden" id="feature_id" value="{{ $feature->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Fields</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th><input type="checkbox" id="check_all"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fields as $field)
                                    <tr>
                                        <td>
                                            <label for="{{ $field->id }}">{{ $field->name }}</label>
                                        </td>
                                        <td>
                                            <label for="{{ $field->id }}">{{ strtoupper($field->type) }}</label>
                                        </td>
                                        <td>
                                            <input type="checkbox" id="{{ $field->id }}"
                                                class="form-check input field_id" name="field_id[]"
                                                value="{{ $field->id }}"
                                                {{ in_array($field->id, $selected_fields) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
