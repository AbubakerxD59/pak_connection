@forelse ($fields as $field)
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
                <textarea class="form-control" name="fields[{{ $field->name }}]" id="{{ $field->name }}" required></textarea>
            @else
                @php
                    $field_name = strtolower($field->name);
                    $value = null;
                    if ($field_name == 'name' || $field_name == 'full name') {
                        $value = auth()->user()->full_name;
                    } elseif ($field_name == 'email') {
                        $value = auth()->user()->email;
                    } elseif ($field_name == 'whatsapp number') {
                        $value = auth()->user()->whatsapp_number;
                    } elseif ($field_name == 'phone number' || $field_name == 'number') {
                        $value = auth()->user()->phone_number;
                    }
                @endphp
                <input type="{{ $field->type }}" class="form-control" name="fields[{{ $field->name }}]"
                    id="{{ $field->name }}" onclick="this.showPicker()" value="{{ $value }}" required>
            @endif
        </div>
    </div>
@empty
    <p class="text-center h4 font-weight-bold">NO FIELDS AVAILABLE</p>
@endforelse
