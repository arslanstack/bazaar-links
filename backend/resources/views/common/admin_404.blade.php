<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ get_section_content('project', 'site_title') }}</title>
    <link href="{{ asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/font-awesome-6.2.0/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">Page Not Found</h3>
        <div class="error-desc"></div>
    </div>
    <script src="{{ asset('admin_assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.js') }}"></script>
</body>
</html>
