<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fields as $field)
            <tr>
                <td>{{ $field->field->name }}</td>
                <td>{{ $field->field->type }}</td>
                <td>{{ $field->value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
