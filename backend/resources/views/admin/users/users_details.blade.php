@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2> Users Detail </h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong> Users Detail </strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-4 col-sm-4 col-xs-4 text-right">
		<a class="btn btn-primary t_m_25" href="{{ url('admin/users') }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Users
		</a>
	</div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="tabs-container">
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active show" role="tabpanel">
						<div class="row">
							<div class="col-md-4">
								<div class="ibox ">
									<div class="ibox-title">
										<h5>Profile Image</h5>
									</div>
									<div>
										<div class="ibox-content no-padding border-left-right text-center">
											<br>
											<img alt="image" class="img-fluid" src="{{ asset('assets/upload_images') }}/{{$user->image_name}}" style="width: 200px; height: auto;">
										</div>
										<div class="ibox-content profile-content">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="ibox">
									<div class="ibox-title">
										<h5>User Detail</h5>
									</div>
									<div class="ibox-content">
										<div>
											<div class="feed-activity-list">
												<div class="row">
													<div class="col-lg-12">
														<div class="row">
															<strong class="col-sm-2 col-form-label">User Name</strong>
															<div class="col-sm-4 col-form-label text-danger">
																{{ $user->name }}
															</div>
															<strong class="col-sm-2 col-form-label">Email</strong>
															<div class="col-sm-4 col-form-label">
																{{ $user->email }}
															</div>
														</div>
														<div class="row">
															<strong class="col-sm-2 col-form-label">Phone No</strong>
															<div class="col-sm-4 col-form-label">
																{{ $user->phone_no }}
															</div>
															<strong class="col-sm-2 col-form-label">City</strong>
															<div class="col-sm-4 col-form-label">
																{{ $user->city }}
															</div>
														</div>
														<div class="row">
															<strong class="col-sm-2 col-form-label">Address</strong>
															<div class="col-sm-4 col-form-label">
																{{ $user->address }}
															</div>
															<strong class="col-sm-2 col-form-label">Zip Code</strong>
															<div class="col-sm-4 col-form-label">
																{{ $user->zip }}
															</div>
														</div>
														<div class="row">
															<strong class="col-sm-2 col-form-label">Joining Date</strong>
															<div class="col-sm-4 col-form-label">
																{{ date_formated($user->created_at) }}
															</div>
															<strong class="col-sm-2 col-form-label">Status</strong>
															<div class="col-sm-4 col-form-label">
																@if ($user->status==1)
																<label class="label label-primary"> Active </label>
																@else
																<label class="label label-danger"> Inactive </label>
																@endif
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$(document).on('click', '.show_bids_tab', function() {
		if (!($("table#manage_tbl").hasClass("dataTable"))) {
			$('#manage_tbl').dataTable({
				"paging": true,
				"searching": true,
				"bInfo": true,
				"responsive": true,
				"columnDefs": [{
						"responsivePriority": 1,
						"targets": 0
					},
					{
						"responsivePriority": 2,
						"targets": -1
					},
				]
			});
		}
	});
	$(document).ready(function() {
		var url = window.location.href;
		var activeTab = url.substring(url.indexOf("#") + 1);
		if (activeTab == 'tab-2') {
			$(".tab-pane").removeClass("active in");
			$("#" + activeTab).addClass("active in");
			$('a[href="#' + activeTab + '"]').tab('show');
			$('#manage_tbl').dataTable({
				"paging": true,
				"searching": true,
				"bInfo": true,
				"responsive": true,
				"columnDefs": [{
						"responsivePriority": 1,
						"targets": 0
					},
					{
						"responsivePriority": 2,
						"targets": -1
					},
					{
						"responsivePriority": 3,
						"targets": -2
					},
				]
			});
		}
	});

	$(document).on("click", ".btn_delete", function() {
		var id = $(this).attr('data-id');
		var car_id = $(this).attr('car-id');
		swal({
				title: "Are you sure?",
				text: "You want to cancel this bid!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, please!",
				cancelButtonText: "No, cancel please!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$(".confirm").prop("disabled", true);
					$.ajax({
						url: "{{ url('admin/cars/cancel_bid') }}",
						type: 'post',
						data: {
							"_token": "{{ csrf_token() }}",
							'id': id,
							'car_id': car_id
						},
						dataType: 'json',
						success: function(status) {
							$(".confirm").prop("disabled", false);
							if (status.msg == 'success') {
								swal({
										title: "Success!",
										text: status.response,
										type: "success"
									},
									function(data) {
										location.reload();
									});
							} else if (status.msg == 'error') {
								swal("Error", status.response, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "", "error");
				}
			});
	});
	$(document).on("click", ".btn_sold", function() {
		var id = $(this).attr('data-id');
		var car_id = $(this).attr('car-id');
		swal({
				title: "Are you sure?",
				text: "You want to mark this car as sold!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, please!",
				cancelButtonText: "No, cancel please!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$(".confirm").prop("disabled", true);
					$.ajax({
						url: "{{ url('admin/cars/sold_car') }}",
						type: 'post',
						data: {
							"_token": "{{ csrf_token() }}",
							'id': id,
							'car_id': car_id
						},
						dataType: 'json',
						success: function(status) {
							$(".confirm").prop("disabled", false);
							if (status.msg == 'success') {
								swal({
										title: "Success!",
										text: status.response,
										type: "success"
									},
									function(data) {
										location.reload();
									});
							} else if (status.msg == 'error') {
								swal("Error", status.response, "error");
							}
						}
					});
				} else {
					swal("Cancelled", "", "error");
				}
			});
	});
</script>
@endpush