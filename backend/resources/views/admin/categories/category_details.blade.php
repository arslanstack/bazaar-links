@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-8 col-sm-8 col-xs-8">
		<h2> Category Details </h2>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ url('admin') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">
				<strong> Category Details </strong>
			</li>
		</ol>
	</div>
	<div class="col-lg-4 col-sm-4 col-xs-4 text-right">
		<a class="btn btn-primary t_m_25" href="{{ url('admin/categories') }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Categories
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
							<div class="col-lg-12">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Category Details</h5>
									</div>
									<div class="ibox-content">
										<form method="post" id="edit_form">
											@csrf
											<input type="hidden" class="form-control" name="id" value="{{$category->id}}">
											<div class="row">
												<div class="col-md-8">
													<div class="form-group row">
														<strong class="col-sm-2 col-form-label">Title</strong>
														<div class="col-sm-10">
															<input type="text" class="form-control" name="title" placeholder="Category Title" value="{{$category->title}}">
														</div>
													</div>
													<div class="form-group row">
														<strong class="col-sm-3 col-form-label"> Is Active/ Enabled </strong>
														<div class="col-sm-1">
															<input class="i-checks" type="checkbox" name="status" @if($category->status == 1) checked @endif>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group row justify-content-center">
														<span class="text-dark">
															<strong>
																<i class="fa fa-solid fa-info-circle text-danger"></i> Category status changes apply to all of its subcategories as well. <br>
																<i class="fa fa-solid fa-info-circle text-danger"></i> Disabling a category prevents new product posts within this category. <br>
																<i class="fa fa-solid fa-info-circle text-danger"></i> Existing posts remain visible.
															</strong>
														</span>
													</div>
												</div>
											</div>

											<!-- <div class="form-group row">
												<strong class="col-sm-2 offset-md-1 col-form-label">Category Image</strong>
												<div class="col-sm-7">
													<input type="file" name="image" class="form-control" accept="image/*">
												</div>
											</div> -->
											<div class="hr-line-dashed" style="margin: 10px 0!important;"></div>
											<div class="form-group row text-right" style="margin-bottom: 0.5rem !important;">
												<div class="col-sm-12 justify-content-end">
													<button type="button" class="btn btn-white" id="cancel_btn" data-url="{{ url('admin/categories') }}">Cancel</button>
													<button type="button" class="ladda-button btn btn-primary" id="btn_update" data-style="expand-right">Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="ibox">
									<div class="ibox-title d-flex justify-content-between align-items-center" style="padding: 15px 20px 8px 15px !important; " id="Subcategories">
										<h5 class="mb-0">Subcategories</h5>
										<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add_modalbox"><i class="fa fa-solid fa-plus"></i> Add Subcategory</button>
									</div>

									<div class="ibox-content">
										<div class="table-responsive">
											<table id="manage_tbl" class="table table-striped dataTable table-bordered dt-responsive" style="width:100%">
												<thead>
													<tr>
														<th>Sr #</th>
														<th>Title</th>
														<th>Creation Date</th>
														<th>Status</th>
														<th class="">Action</th>
													</tr>
												</thead>
												<tbody>
													@php($i = 1)
													@foreach($subcategories as $item)
													<tr class="gradeX">
														<td>{{ $i++ }}</td>
														<td>{{$item->title}}</td>
														<td>{{ date_formated($item->created_at)}}</td>
														<td>
															@if($item->status == 0)
															<label class="label label-warning text-dark"> Disabled </label>
															@else
															<label class="label label-primary"> Enabled </label>
															@endif
														</td>
														<td>
															<button class="btn btn-success btn-sm btn_sub_edit" data-id="{{$item->id}}" type="button"><i class="fa-solid fa-edit"></i> Details</button>
															<button class="btn btn-danger btn-sm btn_delete" data-id="{{$item->id}}" data-text="This action will delete this sub-category." type="button" data-placement="top" title="Delete">Delete</button>
															@if ($item->status==1)
															<button class="btn btn-warning text-dark btn-sm btn_update_status" data-id="{{$item->id}}" data-status="0" data-text="This action will disable this sub-category." type="button" data-placement="top" title="Inactivate">Disable</button>
															@else
															<button class="btn btn-primary btn-sm btn_update_status" data-id="{{$item->id}}" data-status="1" data-text="This action will enable this sub-category." type="button" data-placement="top" title="Activate">Enable</button>
															@endif
														</td>
													</tr>
													@endforeach
												</tbody>
											</table>
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
<div class="modal inmodal show fade" id="add_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5 class="modal-title">Add Subcategory</h5>
			</div>
			<div class="modal-body">
				<form id="add_subcat_form" method="post" enctype="multipart/form-data">
					@csrf
					<input type="text" hidden value="{{$category->id}}" name="category_id">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><strong>Title</strong></label>
						<div class="col-sm-8">
							<input type="text" name="title" class="form-control" placeholder="Subcategory Title">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label"><strong>Image</strong></label>
						<div class="col-sm-8">
							<input type="file" name="image" class="form-control" accept="image/*">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save_subcat_button"> Submit </button>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal show fade" id="edit_modalbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content animated flipInY" id="edit_modalbox_body">
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$('#manage_tbl').dataTable({
		"paging": false,
		"searching": false,
		"bInfo": false,
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
	$(document).ready(function() {
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
	});
	$(document).on("click", "#btn_update", function() {
		var btn = $(this).ladda();
		btn.ladda('start');
		var formData = new FormData($("#edit_form")[0]);
		$.ajax({
			url: "{{ url('admin/categories/update') }}",
			type: 'POST',
			data: formData,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			success: function(status) {
				if (status.msg == 'success') {
					toastr.success(status.response, "Success");
					setTimeout(function() {
						window.location.reload();
					}, 500);
				} else if (status.msg == 'error') {
					btn.ladda('stop');
					toastr.error(status.response, "Error");
					$("#edit_form")[0].reset();
				} else if (status.msg == 'lvl_error') {
					btn.ladda('stop');
					var message = "";
					$.each(status.response, function(key, value) {
						message += value + "<br>";
					});
					btn.ladda('stop');
					toastr.error(message, "Error");
				}
			}
		});
	});

	$(document).on("click", ".btn_sub_edit", function() {
		var id = $(this).attr('data-id');
		$.ajax({
			url: "{{ url('admin/categories/subcategory_show') }}",
			type: 'POST',
			dataType: 'json',
			data: {
				"_token": "{{ csrf_token() }}",
				'id': id
			},
			success: function(status) {
				$("#edit_modalbox_body").html(status.response);
				$("#edit_modalbox").modal('show');
			}
		});
	});
	$(document).on("click", "#update_sub_button", function() {
		var btn = $(this).ladda();
		btn.ladda('start');
		var formData = new FormData($("#edit_sub_form")[0]);
		$.ajax({
			url: "{{ url('admin/categories/update_subcategory') }}",
			type: 'POST',
			data: formData,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			success: function(status) {
				if (status.msg == 'success') {
					toastr.success(status.response, "Success");
					setTimeout(function() {
						location.reload();
					}, 500);
				} else if (status.msg == 'error') {
					btn.ladda('stop');
					toastr.error(status.response, "Error");
				} else if (status.msg == 'lvl_error') {
					btn.ladda('stop');
					var message = "";
					$.each(status.response, function(key, value) {
						message += value + "<br>";
					});
					toastr.error(message, "Error");
				}
			}
		});
	});

	$(document).on("click", ".btn_delete", function() {
		var id = $(this).attr('data-id');
		swal({
				title: "Are you sure?",
				text: "You want to delete this subcategory!",
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
						url: "{{ url('admin/categories/delete_subcategory') }}",
						type: 'post',
						data: {
							"_token": "{{ csrf_token() }}",
							'id': id,
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

	$(document).on("click", ".btn_update_status", function() {
		var id = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		var show_text = $(this).attr('data-text');
		swal({
				title: "Are you sure?",
				text: show_text,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, Please!",
				cancelButtonText: "No, Cancel Please!",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$(".confirm").prop("disabled", true);
					$.ajax({
						url: "{{ url('admin/categories/update_subcategory_status') }}",
						type: 'post',
						data: {
							"_token": "{{ csrf_token() }}",
							'id': id,
							'status': status
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

	$(document).on("change", "#subcategory_img_input", function() {
		var file = this.files[0];
		var fileType = file["type"];
		var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
		if ($.inArray(fileType, validImageTypes) < 0) {
			toastr.error("Invalid image file type. Please select a valid image file.", "Error");
			$("#subcategory_img_input").val('');
			$("#previewImage").attr('src', '');
			return false;
		}
		if (file.size > 2097152) {
			toastr.error("Image file size is too big. Please select a image file less than 2MB.", "Error");
			$("#subcategory_img_input").val('');
			$("#previewImage").attr('src', '');
			return false;
		}
		var reader = new FileReader();
		reader.onload = function(e) {
			$("#previewImage").attr('src', e.target.result);
		};
		reader.readAsDataURL(this.files[0]);
	});
	$(document).on("click", "#save_subcat_button", function() {
		var btn = $(this).ladda();
		btn.ladda('start');
		var formData = new FormData($("#add_subcat_form")[0]);
		$.ajax({
			url: "{{ url('admin/categories/store_subcategory') }}",
			type: 'POST',
			data: formData,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			success: function(status) {
				if (status.msg == 'success') {
					toastr.success(status.response, "Success");
					setTimeout(function() {
						location.reload();
					}, 500);
				} else if (status.msg == 'error') {
					btn.ladda('stop');
					toastr.error(status.response, "Error");
				} else if (status.msg == 'lvl_error') {
					btn.ladda('stop');
					var message = "";
					$.each(status.response, function(key, value) {
						message += value + "<br>";
					});
					toastr.error(message, "Error");
				}
			}
		});
	});
</script>
@endpush