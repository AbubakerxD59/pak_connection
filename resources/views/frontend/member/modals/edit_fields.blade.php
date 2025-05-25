@foreach ($fields as $field)
    <div class="form-group row">
        <div class="col-md-3">
            <label for="{{ $field->field->name }}" class="form-label">{{ $field->field->name }}</label>
        </div>
        <div class="col-md-9">
            @if ($field->field->type == 'dropdown')
                <select class="form-control" name="fields[{{ $field->field->name }}]" id="{{ $field->field->name }}"
                    value="{{ $field->value }}" required disabled>
                    @foreach ($field->field->options as $option)
                        <option value="{{ $option }}" {{ $option == $field->value ? 'selected' : '' }}>
                            {{ str_replace('"', '', $option) }}</option>
                    @endforeach
                </select>
            @elseif($field->field->type == 'textarea')
                <textare class="form-control" name="fields[{{ $field->field->name }}]" id="{{ $field->field->name }}"
                    required disabled>
                    {{ $field->value }}
                    </textarea>
                @else
                    <input type="text" class="form-control" name="fields[{{ $field->field->name }}]"
                        id="{{ $field->field->name }}" value="{{ $field->value }}" required disabled>
            @endif
        </div>
    </div>
@endforeach
