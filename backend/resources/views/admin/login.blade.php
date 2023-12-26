<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ get_section_content('project', 'site_title') }} | Administrator Login</title>
	<link href="{{ asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('admin_assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
	<link href="{{ asset('admin_assets/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('admin_assets/css/custom.css') }}" rel="stylesheet">

	<link href="<?php echo asset('admin_assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo asset('admin_assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
	<link href="<?php echo asset('admin_assets/css/animate.css'); ?>" rel="stylesheet">
	<link href="<?php echo asset('admin_assets/css/style.css'); ?>" rel="stylesheet">
	<link href="<?php echo asset('admin_assets/css/custom.css'); ?>" rel="stylesheet">


	<link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
</head>
<body class="gray-bg">
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div class="col-lg-12 text-center mb-4" style="margin-top: 70px;">
				<img class="img-responsive" src="{{ asset('assets/img') }}/{{ get_section_content('project', 'site_logo') }}" alt="logo" style="width: 241px;">
			</div>
			<h3 class="admin_login_st">Administrator Login</h3>
			@if(session()->has('errors'))
			<div class="single-input-item">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			@endif
			<form class="m-t" role="form" method="post" action="{{ url('admin/verify_login') }}">
				@csrf
				<div class="form-group">
					<input type="email" class="form-control" placeholder="Email" name="email">
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
			</form>
			{{-- <a href="{{ url('admin/forgot_password') }}"><small>Forgot password?</small></a> --}}
			{{-- <p class="text-muted text-center"><small>Do not have an account?</small></p> --}}
			{{-- <a class="btn btn-sm btn-success btn-block" href="{{ url('admin/register') }}">Create an account</a> --}}
			<p class="m-t"> <strong>Copyright</strong> {{ get_section_content('project', 'site_title') }} &copy; {{ date("Y") }} </p>
			<p class="m-t">Developed by - <a href="https://explorelogics.com/" target="_blank">Explore Logics </a></p>
		</div>
	</div>
	<script src="{{ asset('admin_assets/js/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ asset('admin_assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('admin_assets/js/bootstrap.js') }}"></script>
</body>
</html>