@extends('admin.admin_app')
@push('styles')
@endpush
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8 col-sm-8 col-xs-8">
        <h2> Product Post Details </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <strong> Product Post Details </strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 col-sm-4 col-xs-4 text-right">
        <a class="btn btn-primary t_m_25" href="{{ url('admin/product-posts') }}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Posts
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
                                                <h5>Product Images</h5>
                                            </div>
                                            <div>
                                                <div class="ibox-content p-4 border-left-right text-center">
                                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            @php ($i = 0) @endphp
                                                            @foreach(array_slice($post->images, 0, 6) as $image)
                                                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $i }}" @if($i==0) class="active" @endif></li>
                                                            @php ($i++) @endphp
                                                            @endforeach
                                                        </ol>
                                                        <div class="carousel-inner">
                                                            @php ($i = 0) @endphp
                                                            @foreach($post->images as $image)
                                                            <div class="carousel-item @if($i == 0) active @endif">
                                                                <img class="d-block w-100" src="{{ $image }}" alt="First slide" draggable="false" (dragstart)="false;" class="unselectable" style="width: 300px; height: 300px; object-fit: contain;">
                                                            </div>
                                                            @php ($i++) @endphp
                                                            @endforeach
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="ibox-title" style="border: none !important;">
                                                <h5>Product Post Details</h5>
                                                <span class="float-right"><i class="fa-solid fa-heart"></i> {{count_post_favs($post->id)}}</span>
                                            </div>
                                            <div class="ibox-content">
                                                <div>
                                                    <div class="feed-activity-list">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Vendor</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        <a href="{{url('admin/users/detail/' . $post->Vendor->id)}}" target="_blank">{{$post->Vendor->name}} <i class="fa-solid fa-up-right-from-square"></i></a>
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Vendor Location</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->vendor_location}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Title</strong>
                                                                    <div class="col-sm-4 col-form-label text-danger">
                                                                        {{$post->title}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Product Location</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->product_location}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Quantity</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$post->quantity}}
                                                                    </div><strong class="col-sm-3 col-form-label">Unit</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->unit}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Price</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        PKR {{$post->price}} / {{$post->unit}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Brand</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->brand ? $post->brand : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Category</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$post->category}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Subcategory</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->subcategory}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Moisture</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$post->moisture ? $post->moisture : 'N/A'}}%
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Place of Origin</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->place_of_origin ? $post->place_of_origin : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Model No</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{$post->model_no ? $post->model_no : 'N/A'}}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Certification</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        {{$post->certification ? $post->certification : 'N/A'}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <strong class="col-sm-2 col-form-label">Posted On</strong>
                                                                    <div class="col-sm-4 col-form-label">
                                                                        {{ \Carbon\Carbon::parse($post->created_at)->format('j F Y g:i A') }}
                                                                    </div>
                                                                    <strong class="col-sm-3 col-form-label">Status</strong>
                                                                    <div class="col-sm-3 col-form-label">
                                                                        @if($post->status == 0)
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
                                                                        {{ $post->description ? $post->description : 'N/A'}}
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