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
                <strong>Add Category </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/categories') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Category
        </a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Category Details</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" id="add_form">
                        @csrf
                        <div class="form-group row">
                            <strong class="col-sm-2 offset-md-1 col-form-label">Category Title</strong>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="title" placeholder="Category Title">
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <strong class="col-sm-2 offset-md-1 col-form-label">Category Image</strong>
                            <div class="col-sm-7">
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div> -->
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-7 offset-md-3">
                                <button type="button" class="btn btn-white" id="cancel_btn" data-url="{{ url('admin/categories') }}">Cancel</button>
                                <button type="button" class="ladda-button btn btn-primary" id="save_btn" data-style="expand-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('admin_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $(document).on("click", "#save_btn", function() {
        var btn = $(this).ladda();
        btn.ladda('start');
        var formData = new FormData($("#add_form")[0]);
        $.ajax({
            url: "{{ url('admin/categories/store') }}",
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(status) {
                if (status.msg == 'success') {
                    toastr.success(status.response, "Success");
                    $('#add_form')[0].reset();
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
                    btn.ladda('stop');
                    toastr.error(message, "Error");
                }
            }
        });
    });
</script>
@endpush