<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductPost;
use App\Models\ProductRequest;

class ManagePostsController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function activePosts()
    {
        $posts = ProductPost::where('status', 1)
            ->where('vendor_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();
        $posts = $this->addInformationPosts($posts);
        return response()->json(['success' => true, 'message' => 'All Active Product Posts', 'data' => $posts], 200);
    }

    public function activeRequests()
    {
        $prod_reqs = ProductRequest::where('status', 1)
            ->where('vendor_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();
        $prod_reqs = $this->addInformationRequests($prod_reqs);
        return response()->json(['success' => true, 'message' => 'All Product Requests', 'data' => $prod_reqs], 200);
    }

    public function all()
    {
        $posts = ProductPost::where('status', 1)
            ->where('vendor_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();
        $posts = $this->addInformationPosts($posts);
        $prod_reqs = ProductRequest::where('status', 1)
            ->where('vendor_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();
        $prod_reqs = $this->addInformationRequests($prod_reqs);
        $posts_and_requests = $posts->merge($prod_reqs)->sortByDesc('created_at');

        return response()->json(['success' => true, 'message' => 'All Product Posts and Requests', 'data' => $posts_and_requests], 200);
    }

    public function addInformationPosts($posts)
    {
        foreach ($posts as $post) {
            $post->type = 'post';
            $images = json_decode($post->images);
            $images = array_map(function ($item) {
                return url('/public/uploads/products/' . $item);
            }, $images);
            $vendor = $post->Vendor;
            $vendor->city = $vendor->city->city_name;
            $post->vendor = $vendor;
            $post->product_location = $post->Productcity->city_name;
            $post->vendor_location = $post->Vendorcity->city_name;
            $post->unit = $post->Unit->unit_name;
            $post->category = $post->Category->title;
            $post->subcategory = $post->SubCategory->title;
            $post->images = $images;
            $post->favStatus = $post->FavStatus();
            unset($post->unit_id);
            unset($post->category_id);
            unset($post->subcategory_id);
            unset($post->vendor_id);
        }
        return $posts;
    }

    public function addInformationRequests($prod_reqs)
    {
        foreach ($prod_reqs as $prod_req) {
            $prod_req->type = 'request';
            $prod_req->image = url('/public/uploads/products/' . $prod_req->image);
            $vendor = $prod_req->Vendor;
            $vendor->city = $vendor->city->city_name;
            $prod_req->vendor = $vendor;
            $prod_req->product_location = $prod_req->Productcity->city_name;
            $prod_req->vendor_location = $prod_req->Vendorcity->city_name;
            $prod_req->unit = $prod_req->Unit->unit_name;
            $prod_req->category = $prod_req->Category->title;
            $prod_req->subcategory = $prod_req->SubCategory->title;
            $prod_req->favStatus = $prod_req->FavStatus();
            unset($prod_req->unit_id);
            unset($prod_req->category_id);
            unset($prod_req->subcategory_id);
            unset($prod_req->vendor_id);
        }
        return $prod_reqs;
    }
}
