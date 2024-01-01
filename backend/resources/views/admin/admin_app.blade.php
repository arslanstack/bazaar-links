<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ get_section_content('project', 'site_title') }} | Administrator Panel</title>
	@include('common.admin_header')
	<style>
		.carousel-indicators li {
			background-color: #999 !important;
			background-color: rgba(70, 70, 70, .25) !important;
		}

		.carousel-indicators .active {
			background-color: #444 !important;
		}
	</style>
</head>

<body>
	<div id="wrapper">
		@include('common.admin_sidebar')
		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				@include('common.admin_logoutbar')
			</div>

			@yield('content')

			@include('common.admin_footer')
		</div>
	</div>
	@include('common.admin_scripts')
</body>

</html>