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
                <td>{{ $field->getField() ? $field->getField()->name : '' }}</td>
                <td>{{ $field->getField() ? $field->getField()->type : '' }}</td>
                <td>{{ $field->value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
