<script>
    var services_pdf_dataTable = '';
    $(document).ready(function() {
        // datatable
        services_pdf_dataTable = $('#services_pdf_dataTable').DataTable({
            "paging": true,
            'iDisplayLength': 10,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('booked-services.pdf.dataTable') }}",
            },
            columns: [
                // {
                //     data: 'id'
                // },
                {
                    data: 'subject'
                },
                {
                    data: 'text'
                },
                {
                    data: 'action'
                }
            ],
        });
        // view details
       
    });
</script>