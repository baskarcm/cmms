@php
    $user = Auth::user();
    $currentUrl = url()->current();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ config('app.name', 'DEMS') }} @isset($title) :: {{ $title }} @endisset</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('admin/assets/img/favicon.ico') }}" type="image/x-icon"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts and icons -->
    <script src="{{ asset('admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{ asset('admin/assets/css/fonts.min.css') }}']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <!-- <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}"> -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}">
    
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('common/vendor/datatable/datatables.min.css') }}">

    <!-- X Editable CSS -->
    <link rel="stylesheet" href="{{ asset('common/vendor/x-editable/css/bootstrap-editable.css') }}">

    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('common/vendor/select2/css/select2.min.css') }}">

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('common/vendor/daterangepicker/daterangepicker.css') }}">

    <!-- Image Picker CSS -->
    <link rel="stylesheet" href="{{ asset('common/vendor/fileinput/css/jasny-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/assets/css/atlantis.css') }}">

    <link rel="stylesheet" href="{{ asset('common/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
    @stack("css")
    <style>
        .navbar-header[data-background-color="blue2"] {
    background: #691b8d !important;
}
    </style>

</head>
<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="blue">
                
                <a href="{{ route('private.dashboard') }}" class="logo text-center">
                    <img src="{{ asset('common/img/logo/new_zaar_logo.png') }}" alt="navbar brand" class="navbar-brand w-50">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" style="background-color:#030048 !important">
                
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item toggle-nav-search hidden-caret">
                            <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>
                        
                        <!--<li class="nav-item dropdown hidden-caret">-->
                        <!--    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                        <!--        <i class="fa fa-bell"></i>-->
                        <!--        <span class="notification">0</span>-->
                        <!--    </a>-->
                        <!--    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">-->
                        <!--        <li>-->
                        <!--            <div class="dropdown-title">You have 0 new notification</div>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ empty($user->profile_pic) ? avatar($user->name) : asset($user->profile_pic) }}" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img src="{{ empty($user->profile_pic) ? avatar($user->name) : asset($user->profile_pic) }}" alt="image profile" class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4>{{ $user->name }}</h4>
                                                <p class="text-muted">{{ $user->email }}</p>
                                                <a href="{{ route('private.myprofile') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('private.logout') }}">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2">           
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-primary">
                        <li class="nav-item @if($currentUrl == route('private.dashboard')) active @endif">
                            <a href="{{ route('private.dashboard') }}">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                                <!-- <span class="caret"></span> -->
                            </a>
                        </li>
                        <li class="nav-item @if($currentUrl == route('private.schedule')) active @endif">
                            <a href="{{ route('private.schedule') }}">
                                <i class="far fa-calendar-alt"></i>
                                <p>Schedule</p>
                                <!-- <span class="caret"></span> -->
                            </a>
                        </li>
                        <li class="nav-item  @if($currentUrl == route('private.pm') || $currentUrl == route('private.breakdown') ) active submenu @endif">
                            <a data-toggle="collapse" href="#module">
                                <i class="fas fa-layer-group"></i>
                                <p>Daily Reports</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse  @if($currentUrl == route('private.pm') || $currentUrl == route('private.breakdown')) show @endif" id="module">
                                <ul class="nav nav-collapse">
                                    <li class="@if($currentUrl == route('private.pm')) active @endif ">
                                        <a href="{{ route('private.pm') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>PM</p>
                                            </span>
                                        </a>
                                    </li>
                                     <li class="@if($currentUrl == route('private.breakdown')) active @endif ">
                                        <a href="{{ route('private.breakdown') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Breakdown</p>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item  @if($currentUrl == route('private.pm.report') || $currentUrl == route('private.breakdown.report')  || $currentUrl == route('private.month.view') ) active submenu @endif">
                            <a data-toggle="collapse" href="#report">
                                <i class="fas fa-window-restore"></i>
                                <p>Reports</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse  @if($currentUrl == route('private.pm.report') || $currentUrl == route('private.breakdown.report')  || $currentUrl == route('private.month.view') ) show @endif" id="report">
                                <ul class="nav nav-collapse">
                                    <li class="@if($currentUrl == route('private.pm.report')) active @endif ">
                                        <a href="{{ route('private.pm.report') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>PM</p>
                                            </span>
                                        </a>
                                    </li>
                                     <li class="@if($currentUrl == route('private.breakdown.report')) active @endif ">
                                        <a href="{{ route('private.breakdown.report') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Breakdown</p>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="@if($currentUrl == route('private.actual.report')) active @endif ">
                                        <a href="{{ route('private.actual.report') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Plan Vs Actual</p>
                                            </span>
                                        </a>
                                    </li>
                                     <li class="@if($currentUrl == route('private.month.view')) active @endif ">
                                        <a href="{{ route('private.month.view') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Month Report</p>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="nav-item @if($currentUrl == route('private.inventory')) active @endif">
                            <a href="{{ route('private.inventory') }}">
                                <i class="fas fa-people-carry"></i>
                                <p>Inventory</p>
                                <!-- <span class="caret"></span> -->
                            </a>
                        </li>
                    @if($user->user_type == 1)
                        <li class="nav-item @if($currentUrl == route('private.form')) active @endif">
                            <a href="{{ route('private.form') }}">
                                <i class="far fa-calendar-check"></i>
                                <p>Check List</p>
                                <!-- <span class="caret"></span> -->
                            </a>
                        </li>
                        <li class="nav-item  @if($currentUrl == route('private.product') || $currentUrl == route('private.insPoint') || $currentUrl == route('private.insIteam') || $currentUrl == route('private.judge') ) active submenu @endif">
                            <a data-toggle="collapse" href="#product">
                                <i class="fas fa-box-open"></i>
                                <p>Equipments</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse  @if($currentUrl == route('private.product') || $currentUrl == route('private.insPoint') || $currentUrl == route('private.insIteam') || $currentUrl == route('private.judge')) show @endif" id="product">
                                <ul class="nav nav-collapse">
                                    <li class="@if($currentUrl == route('private.product')) active @endif ">
                                        <a href="{{ route('private.product') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Equipments</p>
                                            </span>
                                        </a>
                                    </li>
                                     <li class="@if($currentUrl == route('private.insPoint')) active @endif ">
                                        <a href="{{ route('private.insPoint') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Inspection Point</p>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="@if($currentUrl == route('private.insIteam')) active @endif ">
                                        <a href="{{ route('private.insIteam') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Inspection Items</p>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="@if($currentUrl == route('private.judge')) active @endif ">
                                        <a href="{{ route('private.judge') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Judge Std</p>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item  @if($currentUrl == route('private.users') || $currentUrl == route('private.adminusers') || $currentUrl == route('private.admintypes') || $currentUrl == route('private.usertype') ) active submenu @endif">
                            <a data-toggle="collapse" href="#base">
                                <i class="fas fa-users"></i>
                                <p>Settings</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse  @if($currentUrl == route('private.users') || $currentUrl == route('private.adminusers') || $currentUrl == route('private.admintypes') || $currentUrl == route('private.usertype')) show @endif" id="base">
                                <ul class="nav nav-collapse">
                                    <li class="@if($currentUrl == route('private.users')) active @endif">
                                        <a href="{{ route('private.users') }}">
                                            <span class="sub-item">
                                                <!-- <i class="fas fa-users"></i> -->
                                                <p>Users</p>
                                            </span>
                                            <!-- <span class="caret"></span> -->
                                        </a>

                                    </li>
                                    <li class="@if($currentUrl == route('private.adminusers')) active @endif ">
                                        <a href="{{ route('private.adminusers') }}">
                                            <span class="sub-item">
                                                <!-- <i class="fas fa-user-cog"></i> -->
                                                <p>Admin Users</p>
                                            </span>
                                        </a>
                                    </li>
                                     <!-- <li class="@if($currentUrl == route("private.admintypes")) active @endif ">
                                        <a href="{{ route('private.admintypes') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>Admin Type</p>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="@if($currentUrl == route("private.usertype")) active @endif ">
                                        <a href="{{ route('private.usertype') }}">
                                            <span class="sub-item">
                                                {{-- <i class="fas fa-user-shield"></i> --}}
                                                <p>User Type</p>
                                            </span>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                        </li>
                    @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                @yield('content')
                
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright ml-auto">
                        &copy; {{ date('Y') }}, ZAAR</a>
                    </div>              
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    
    @include('includes.modal')

    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- jQuery UI -->
    <script src="{{ asset('admin/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('admin/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugin/jquery.magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    
    <!-- jQuery Scrollbar -->
    <script src="{{ asset('admin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- Emoji -->
    <script src="//twemoji.maxcdn.com/2/twemoji.min.js?12.0.0"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('admin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Atlantis JS -->
    <script src="{{ asset('admin/assets/js/atlantis.js') }}"></script>
    <!-- Data Table JS -->
    <script src="{{ asset('common/vendor/datatable/datatables.min.js') }}"></script>
    
    <!-- X Editable JS -->
    <script src="{{ asset('common/vendor/x-editable/js/bootstrap-editable.min.js') }}"></script>

    <!-- Select 2 -->
    <script src="{{ asset('common/vendor/select2/js/select2.full.min.js') }}"></script>
    
    <!-- Daterange Picker -->
    <script src="{{ asset('common/vendor/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Image Picker -->
    <script src="{{ asset('common/vendor/fileinput/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('common/vendor/fileinput/js/fileinput.js') }}"></script>
    
    <!-- Validate -->
    <script src="{{ asset('common/vendor/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('common/vendor/validate/additional-methods.js') }}"></script>

    <script src="{{ asset('common/js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
    <script src="{{ asset('admin/assets/js/plugin/chart.js/chart.min.js') }}"></script>

    
    <script>
        var dateFormat = "{{ config("site.date_format.front") }}";
        var dateTimeFormat = "{{ config("site.date_time_format.front") }}";
        var notify = "";
        //Initialize Emoji
        $('body').bind("DOMSubtreeModified",function(){
            twemoji.parse(document.body);
        });
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $( document ).ajaxError(function( event, request, settings ) {
                notifyWarning("Something went wrong");
            });
        });

        $('.image-gallery').magnificPopup({
            delegate: 'a', 
            type: 'image',
            removalDelay: 300,
            gallery:{
                enabled:true,
            },
            mainClass: 'mfp-with-zoom', 
            zoom: {
                enabled: true, 
                duration: 300,
                easing: 'ease-in-out',
                opener: function(openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });

    </script>

    @stack("js")

    @stack("include_js")
    
</body>
</html>

{{-- asset('admin/assets/img/profile.jpg') --}}