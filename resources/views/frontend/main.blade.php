<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/frontend-styles.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
</head>

<body>
    @yield('body')
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    @if (Session::has('success'))
        <script type="text/javascript">
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif
    @if (Session::has('warning'))
        <script type="text/javascript">
            toastr.warning('{{ Session::get('warning') }}');
        </script>
    @endif
    @if (Session::has('error'))
        <script type="text/javascript">
            toastr.error('{{ Session::get('error') }}');
        </script>
    @endif
</body>

</html>
