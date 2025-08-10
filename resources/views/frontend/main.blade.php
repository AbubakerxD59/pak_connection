<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>Membership Portal</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/frontend-styles.css') }}">
</head>

<body>
    <div class="content-wrapper">
        <header class="header">
            <div class="container">
                <div class="logo">
                    <a href="{{ route('frontend.home') }}"><img src="{{ asset('assets/img/site_logo.jpg') }}"
                            alt="Pak Connection"></a>
                </div>
                <div class="slogan-title">
                    <h1>Membership Portal</h1>
                </div>
                <div class="login-holder">
                    @if (auth()->check())
                        <div class="dropdown">
                            <div class="dropdown-toggle" id="loginDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="costomer-login">
                                    <div class="customer-avatar">
                                        <img src="{{ asset('assets/img/avatar2.png') }}" alt="Avatar">
                                    </div>
                                    <div class="txtbox">
                                        <h3><a  href="{{ route('frontend.member.profile') }}">{{ auth()->user()->full_name }}</a></h3>
                                        <p><a href="{{ route('frontend.member.profile') }}">{{ auth()->user()->email }}</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="loginDropdown">
                                <a class="dropdown-item" href="#">View Profile</a>
                                <a class="dropdown-item" href="#">Service Tracking</a>
                            </div>
                        </div>
                        <form action="{{ route('frontend.member.logout') }}" method="POST" id="signout_form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        </form>
                        <div>
                            <i class="fas fa-sign-out-alt sign-out pointer  " style="font-size: 20px;"></i>
                        </div>
                    @else
                        <a class="btn btn-primary" href="{{ route('frontend.showLogin') }}">Already A Member</a>
                    @endif
                </div>
            </div>
        </header>
        @yield('body')
        <footer class="footer">
            <div class="container footer-top">
                <div class="row">
                    <div class="col-sm-6 col-md-3 mb-4 mb-md-0 footer-cols">
                        <div class="logo">
                            <a href="/"><img src="{{ asset('assets/img/site_logo.jpg') }}"
                                    alt="Pak Connection"></a>
                        </div>
                        <p>Pak Connections provides a diverse range of services that are customised to cater to your
                            requirements.</p>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4 mb-md-0 footer-cols">
                        <div class="holder">
                            <h3>Company</h3>
                            <ul class="footer-nav">
                                <li><a href=""><i class="fa fa-chevron-right"></i> Services</a></li>
                                <li><a href=""><i class="fa fa-chevron-right"></i> About Us</a></li>
                                <li><a href=""><i class="fa fa-chevron-right"></i> Membership</a></li>
                                <li><a href=""><i class="fa fa-chevron-right"></i> Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4 mb-md-0 footer-cols">
                        <div class="holder">
                            <h3>Services</h3>
                            <ul class="footer-nav">
                                <li><a href=""><i class="fa fa-chevron-right"></i> Privacy Policy & GDPR</a></li>
                                <li><a href=""><i class="fa fa-chevron-right"></i> Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4 mb-md-0 footer-cols">
                        <div class="holder">
                            <h3>Get in Touch</h3>
                            <ul class="footer-nav contact">
                                <li><a href="tel:+9252000111"><i class="fa fa-phone"></i> +92-52-000111</a></li>
                                <li class="email"><a href="mailto:info@pakconnection.com"><i
                                            class="fa fa-envelope"></i> info@pakconnection.com</a></li>
                            </ul>
                            <ul class="social-networks">
                                <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                                <li><a href="#"><i class="bi bi-twitter"></i></a></li>
                                <li><a href="#"><i class="bi bi-linkedin"></i></a></li>
                                <li><a href="#"><i class="bi bi-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6">
                            <ul class="footer-botton-nav copyright">
                                <li>&copy; Copyright – 2025</li>
                                <li><a href="/">PakConnections</a></li>
                                <li>All Rights Reserved</li>
                            </ul>
                        </div>
                        <div class="col-xl-6">
                            <ul class="footer-botton-nav privacy">
                                <li><a href="">Privacy Policy & GDPR</a></li>
                                <li><a href="">Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
        $(document).ready(function() {
            $('.sign-out').on('click', function() {
                if (confirm('Do you wish to Signout?')) {
                    console.log('here');
                    $('#signout_form').submit();
                }
            });
        });
    </script>
    @stack('script')
</body>

</html>
