@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2> Product Request Details </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong> Product Request Details </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/product-requests') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to All Request
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
                            <div class="col-md-12">
                                <div class="ibox">
                                    <div class="row ibox-content" style="border: none !important;">
                                        <div class="col-md-4">
                                            <div class="ibox-title" style="border: none !important;">
                                                <h5>Product Image</h5>
                                            </div>
                                            <div>
                                                <div class="ibox-content p-4 border-left-right text-center">
                                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                        <img class="d-block w-100" src="{{asset('uploads/products/' . $prod_req->image )}}" alt="First slide" draggable="false" (dragstart)="false;" class="unselectable" style="width: 250px; height: 250px; object-fit: contain;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="ibox-title" style="border: none !important;">
                                                <h5>Product Request Details</h5>
                                                <span class="float-right"><i class="fa-solid fa-heart"></i> {{count_request_favs($prod_req->id)}}</span>
                                            </div>
                                            <div class="ibox-content">
                                                <div>
                                                    <div class="feed-activity-list">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Title</strong>
                                                                    <div class="col-sm-10 col-form-label text-danger">
                                                                        {{$prod_req->title}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Vendor</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        <a href="{{url('admin/users/detail/' . $prod_req->Vendor->id)}}" target="_blank">{{$prod_req->Vendor->name}} <i class="fa-solid fa-up-right-from-square"></i></a>
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Vendor Location</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->vendor_location}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Quantity</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$prod_req->quantity}}
                                                                    </div><strong class="col-sm-3 col-form-label">Unit</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->unit}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Price</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        PKR {{$prod_req->price}} / {{$prod_req->unit}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Brand</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->brand ? $prod_req->brand : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Category</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$prod_req->category}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Subcategory</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->subcategory}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Moisture</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$prod_req->moisture ? $prod_req->moisture : 'N/A'}}%
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Place of Origin</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->place_of_origin ? $prod_req->place_of_origin : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Model No</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$prod_req->model_no ? $prod_req->model_no : 'N/A'}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Certification</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$prod_req->certification ? $prod_req->certification : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Posted On</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{ \Carbon\Carbon::parse($prod_req->created_at)->format('j F Y g:i A') }}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Status</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        @if($prod_req->status == 0)
                                                                        <label class="label label-warning text-dark"> Disabled </label>
                                                                        @else
                                                                        <label class="label label-primary"> Active </label>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="ibox-title" style="border: none !important;">
                                                <h5>Product Description</h5>
                                            </div>
                                            <div class="ibox-content">
                                                <div>
                                                    <div class="feed-activity-list">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-sm-12 col-form-label">
                                                                        {{ $prod_req->description ? $prod_req->description : 'N/A'}}
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
    </div>
</div>
@endsection
@push('scripts')
@endpush