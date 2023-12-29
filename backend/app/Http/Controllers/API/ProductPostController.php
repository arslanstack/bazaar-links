<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductPost;
use App\Models\City;
use App\Models\Unit;
use App\Models\User;
use App\Models\Category;
use App\Models\Favourite;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductPostController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'showCategoryPosts', 'showSubCategoryPosts', 'showCityPosts']]);
    }

    public function index()
    {
        $posts = ProductPost::where('status', 1)->orderBy('id', 'desc')->get();
        $posts = $this->addInformation($posts);
        return response()->json(['success' => true, 'message' => 'All Product Posts', 'data' => $posts], 200);
    }
    public function show($post_id)
    {
        $post = ProductPost::where('id', $post_id)->first();
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Product Post Not Found'], 404);
        }
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

        return response()->json(['success' => true, 'message' => 'Product Post', 'data' => $post], 200);
    }
    public function showCategoryPosts($category_id)
    {
        $posts = ProductPost::where('category_id', $category_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $posts = $this->addInformation($posts);
        return response()->json(['success' => true, 'message' => 'All Product Posts in Category', 'data' => $posts], 200);
    }
    public function showSubCategoryPosts($subcategory_id)
    {
        $posts = ProductPost::where('subcategory_id', $subcategory_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $posts = $this->addInformation($posts);
        return response()->json(['success' => true, 'message' => 'All Product Posts in Subcategory', 'data' => $posts], 200);
    }

    public function showCityPosts($city_id)
    {
        $posts = ProductPost::where('product_location', $city_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $posts = $this->addInformation($posts);
        return response()->json(['success' => true, 'message' => 'All Product Posts in City', 'data' => $posts], 200);
    }

    public function store(Request $request)
    {
        $ImageFiles = $request->images;
        $images = [];
        if ($ImageFiles != null) {
            foreach ($ImageFiles as $file) {
                $validator = Validator::make(
                    [
                        'file' => $file,
                        'extension' => strtolower($file->getClientOriginalExtension()),
                        'mime' => $file->getMimeType(),
                    ],
                    [
                        'file' => 'required|file',
                        'extension' => 'required|string|in:jpg,jpeg,png',
                        'mime' => 'required|string|in:image/jpeg,image/png',
                    ]
                );

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid File',
                        'errors' => $validator->errors(),
                    ], 400);
                } else {
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $name = 'prod_req_' . time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = public_path('/uploads/products');
                    $file->move($destinationPath, $name);
                    array_push($images, $name);
                }
            }
        }

        $validee = Validator::make($request->all(), [
            'title' => 'required',
            'product_location' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
        ]);

        if ($validee->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all required fields',
                'errors' => $validee->errors(),
            ], 400);
        }
        $prod_city = City::where('id', $request->product_location)->pluck('id')->first();
        $vendor_id = Auth::user()->id;
        $vendor_location = Auth::user()->city->id;

        $post = new ProductPost();
        $post->title = $request->title;
        $post->product_location = $prod_city;
        $post->unit_id = $request->unit_id;
        $post->price = $request->price;
        $post->quantity = $request->quantity;
        $post->category_id = $request->category_id;
        $post->subcategory_id = $request->subcategory_id;
        $post->moisture = $request->moisture;
        $post->place_of_origin = $request->place_of_origin;
        $post->brand = $request->brand;
        $post->model_no = $request->model_no;
        $post->certification = $request->certification;
        $post->description = $request->description;
        $post->images = json_encode($images);
        $post->status = 1;
        $post->vendor_id = $vendor_id;
        $post->vendor_location = $vendor_location;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Post Created Successfully',
            'data' => $post
        ], 201);
    }

    public function deactivate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all required fields',
                'errors' => $validation->errors(),
            ], 400);
        }

        $post = ProductPost::find($request->id);
        $post->status = 0;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Post Deactivated Successfully'
        ], 200);
    }

    public function activate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all required fields',
                'errors' => $validation->errors(),
            ], 400);
        }

        $post = ProductPost::find($request->id);
        $post->status = 1;
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Post Activated Successfully'
        ], 200);
    }

    public function destroy(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please fill all required fields',
                'errors' => $validation->errors(),
            ], 400);
        }

        $post = ProductPost::find($request->id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Product Post Not Found',
            ], 404);
        }
        // delete images first
        $images = json_decode($post->images);
        foreach ($images as $image) {
            $image_path = public_path('/uploads/products/' . $image);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $favs = Favourite::where('post_id', $post->id)->where('post_type', 0)->get();
        foreach ($favs as $fav) {
            $fav->delete();
        }
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Post Deleted Successfully'
        ], 200);
    }

    public function addInformation($posts)
    {
        foreach ($posts as $post) {
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
}
