$(document).ready(function () {
    $(document).on('change', '.pdf_file', function (event) {
        var parent = $(this).closest('form');
        const file = event.target.files[0];
        if (file && file.type === "application/pdf") {
            const fileURL = URL.createObjectURL(file);
            parent.find('#pdfPreview').attr('src', fileURL);
            parent.find('#pdfPreview').show();
        } else {
            parent.find('#pdfPreview').attr('src', '');
            parent.find('#pdfPreview').hide();
        }
    });
});