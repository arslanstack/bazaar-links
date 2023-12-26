@extends('admin.admin_app')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2>Dashboard</h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong>Change Password</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Change Password</h5>
				</div>
				<div class="ibox-content">
					<div class="change_pass middle-box animated fadeInDown">
						<form role="form" id="change_password_form">
							@csrf
							<div class="row" id="pwd-container3">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="old_password">Old Password</label>
										<input type="password" class="form-control" name="old_password" id="old_password" placeholder="">
									</div>
									<div class="form-group">
										<label for="new_password">New Password</label>
										<input type="password" name="new_password" class="form-control" id="new_password" placeholder="">
									</div>
									<div class="form-group">
										<label for="c_password">Confirm Password</label>
										<input type="password" class="form-control" name="c_password" id="c_password" placeholder="">
									</div>
									<button type="button" id="submit" class="btn btn-primary block full-width m-b">Change</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
	$('#change_password_form').validate({
		errorElement: 'span',
		errorClass: 'text-danger',
		focusInvalid: true,
		ignore: "",
		rules: {
			old_password: {
				required: true,
			},
			new_password: {
				required: true,
				minlength: 6
			},
			c_password: {
				required: true,
				equalTo:"#new_password"
			},
		},
		messages: {
			old_password: "Please enter old password.",
			new_password: {
				required : "Please enter new password",
				minlength : "Password must be greater than 6 digits.",
			},
			c_password:{
				required : "Please enter confirm password.",
				equalTo : "Confirm password does not match.",
			},
		},
	});
	$('#submit').click(function(e){
		if($("#change_password_form").valid()){
			var btn = $(this).ladda();
			btn.ladda('start');
			var value = $("#change_password_form").serialize();
			$.ajax({
				url:'{{ url('admin/update_password') }}',
				type:'post',
				data:value,
				dataType:'json',
				success:function(status){
					btn.ladda('stop');
					if(status.msg=='success'){
						$('#change_password_form')[0].reset();
						toastr.success(status.response,"Success");
					} else if(status.msg == 'error'){
						toastr.error(status.response,"Error");
					} else if(status.msg == 'lvl_error') {
						var message = "";
						$.each(status.response, function (key, value) {
							message += value+"<br>";
						});
						toastr.error(message, "Error");
					}

				}
			});
		}
	});
</script>
@endpush