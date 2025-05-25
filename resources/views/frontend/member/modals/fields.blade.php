@foreach ($fields as $field)
    <div class="form-group row">
        <div class="col-md-3">
            <label for="{{ $field->name }}" class="form-label">{{ $field->name }}</label>
        </div>
        <div class="col-md-9">
            @if ($field->type == 'dropdown')
                <select class="form-control" name="fields[{{ $field->name }}]" id="{{ $field->name }}" required>
                    @foreach ($field->options as $option)
                        <option value="{{ $option }}">{{ str_replace('"', '', $option) }}</option>
                    @endforeach
                </select>
            @elseif($field->type == 'textarea')
                <textarea class="form-control" name="fields[{{ $field->name }}]" id="{{ $field->name }}" required>
                    </textarea>
            @else
                <input type="{{ $field->type }}" class="form-control" name="fields[{{ $field->name }}]"
                    id="{{ $field->name }}" required>
            @endif
        </div>
    </div>
@endforeach
