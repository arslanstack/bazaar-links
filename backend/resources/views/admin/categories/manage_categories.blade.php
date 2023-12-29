@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2> Categories </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong> Categories </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/categories/add') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add New Category
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form id="search_form" action="{{url('admin/categories')}}" method="GET" enctype="multipart/form-data">
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search_query" placeholder="Search by Title" value="{{ old('search_query', $searchParams['search_query'] ?? '') }}">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="manage_tbl" class="table table-striped table-bordered dt-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr #</th>
                                    <th>Title</th>
                                    <th>Sub-categories</th>
                                    <th>Creation Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach($categories as $item)
                                <tr class="gradeX">
                                    <td>{{ $i++ }}</td>
                                    <td>{{$item->title}}</td>
                                    <td>
                                        @if($item->subcategories->count() > 0)
                                        <a href="{{ url('admin/categories/detail') }}/{{ $item->id }}/#Subcategories"><strong>{{ $item->subcategories->count() }}</strong> (View) </a>
                                        @else
                                        None
                                        @endif
                                    </td>
                                    <td>{{ date_formated($item->created_at)}}</td>
                                    <td>
                                        @if($item->status == 0)
                                        <label class="label label-warning text-dark"> Disabled </label>
                                        @else
                                        <label class="label label-primary"> Enabled </label>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="{{ url('admin/categories/detail') }}/{{ $item->id }}"><i class="fa-solid fa-edit"></i> Details</a>
                                        <button class="btn btn-danger btn-sm btn_delete" data-id="{{$item->id}}" data-text="This action will delete this category." type="button" data-placement="top" title="Delete">Delete</button>
                                        <!-- @if ($item->status==1)
                                        <button class="btn btn-warning text-dark btn-sm btn_update_status" data-id="{{$item->id}}" data-status="0" data-text="This action will disable this category and all it's subsequent sub-categories." type="button" data-placement="top" title="Inactivate">Disable</button>
                                        @else
                                        <button class="btn btn-primary btn-sm btn_update_status" data-id="{{$item->id}}" data-status="1" data-text="This action will enable this category and all it's subsequent sub-categories." type="button" data-placement="top" title="Activate">Enable</button>
                                        @endif -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <p>Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries</p>
                        </div>
                        <div class="col-md-3 text-right">
                            {{ $categories->links('pagination::bootstrap-4') }}
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


    // $(document).on("click", ".btn_update_status", function() {
    //     var id = $(this).attr('data-id');
    //     var status = $(this).attr('data-status');
    //     var show_text = $(this).attr('data-text');
    //     swal({
    //             title: "Are you sure?",
    //             text: show_text,
    //             type: "warning",
    //             showCancelButton: true,
    //             confirmButtonColor: "#DD6B55",
    //             confirmButtonText: "Yes, Please!",
    //             cancelButtonText: "No, Cancel Please!",
    //             closeOnConfirm: false,
    //             closeOnCancel: true
    //         },
    //         function(isConfirm) {
    //             if (isConfirm) {
    //                 $(".confirm").prop("disabled", true);
    //                 $.ajax({
    //                     url: "{{ url('admin/categories/update_statuses') }}",
    //                     type: 'post',
    //                     data: {
    //                         "_token": "{{ csrf_token() }}",
    //                         'id': id,
    //                         'status': status
    //                     },
    //                     dataType: 'json',
    //                     success: function(status) {
    //                         $(".confirm").prop("disabled", false);
    //                         if (status.msg == 'success') {
    //                             swal({
    //                                     title: "Success!",
    //                                     text: status.response,
    //                                     type: "success"
    //                                 },
    //                                 function(data) {
    //                                     location.reload();
    //                                 });
    //                         } else if (status.msg == 'error') {
    //                             swal("Error", status.response, "error");
    //                         }
    //                     }
    //                 });
    //             } else {
    //                 swal("Cancelled", "", "error");
    //             }
    //         });
    // });

    $(document).on("click", ".btn_delete", function() {
		var id = $(this).attr('data-id');
		swal({
				title: "Are you sure?",
				text: "You want to delete this category!",
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
						url: "{{ url('admin/categories/delete_category') }}",
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
</script>
@endpush