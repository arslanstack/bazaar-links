<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRequest;
use App\Models\City;
use App\Models\Favourite;
use Illuminate\Support\Facades\Validator;

class ProductRequestController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'showCategoryRequests', 'showSubCategoryRequests', 'showCityRequests']]);
    }

    public function index()
    {
        $requests = ProductRequest::where('status', 1)->orderBy('id', 'desc')->get();
        $requests = $this->addInformation($requests);
        return response()->json(['success' => true, 'message' => 'All Product Requests', 'data' => $requests], 200);
    }

    public function show($req_id)
    {
        $prod_req = ProductRequest::where('id', $req_id)->first();
        if (!$prod_req) {
            return response()->json(['success' => false, 'message' => 'Product Request Not Found'], 404);
        }
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

        return response()->json(['success' => true, 'message' => 'Product Request', 'data' => $prod_req], 200);
    }
    public function showCategoryRequests($category_id)
    {
        $requests = ProductRequest::where('category_id', $category_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $requests = $this->addInformation($requests);
        return response()->json(['success' => true, 'message' => 'All Product Requests in Category', 'data' => $requests], 200);
    }
    public function showSubCategoryRequests($subcategory_id)
    {
        $requests = ProductRequest::where('subcategory_id', $subcategory_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $requests = $this->addInformation($requests);
        return response()->json(['success' => true, 'message' => 'All Product Requests in Subcategory', 'data' => $requests], 200);
    }

    public function showCityRequests($city_id)
    {
        $requests = ProductRequest::where('product_location', $city_id)->where('status', 1)->orderBy('id', 'desc')->get();
        $requests = $this->addInformation($requests);
        return response()->json(['success' => true, 'message' => 'All Product Requests in City', 'data' => $requests], 200);
    }

    public function store(Request $request)
    {
        $validee = Validator::make($request->all(), [
            'title' => 'required',
            'product_location' => 'required',
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

        $prod_req = new ProductRequest();
        $prod_req->title = $request->title;
        $prod_req->product_location = $prod_city;
        $prod_req->unit_id = $request->unit_id;
        $prod_req->quantity = $request->quantity;
        $prod_req->category_id = $request->category_id;
        $prod_req->subcategory_id = $request->subcategory_id;
        $prod_req->moisture = $request->moisture;
        $prod_req->place_of_origin = $request->place_of_origin;
        $prod_req->brand = $request->brand;
        $prod_req->model_no = $request->model_no;
        $prod_req->certification = $request->certification;
        $prod_req->description = $request->description;
        $prod_req->status = 1;
        $prod_req->vendor_id = $vendor_id;
        $prod_req->vendor_location = $vendor_location;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $name = 'prod_req_' . time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('/uploads/products');
            $file->move($destinationPath, $name);
            $prod_req->image = $name;
        }
        $prod_req->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Request Created Successfully',
            'data' => $prod_req
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

        $prod_req = ProductRequest::find($request->id);
        if(!$prod_req){
            return response()->json([
                'success' => false,
                'message' => 'Product Request Not Found',
            ], 404);
        }
        $prod_req->status = 0;
        $prod_req->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Request Deactivated Successfully'
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

        $prod_req = ProductRequest::find($request->id);
        if(!$prod_req){
            return response()->json([
                'success' => false,
                'message' => 'Product Request Not Found',
            ], 404);
        }
        $prod_req->status = 1;
        $prod_req->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Request Activated Successfully'
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

        $prod_req = ProductRequest::find($request->id);
        if (!$prod_req) {
            return response()->json([
                'success' => false,
                'message' => 'Product Request Not Found',
            ], 404);
        }
        // delete image first
        // $image_path = public_path('/uploads/products/' . $prod_req->image);
        // if(file_exists($image_path)){
        //     unlink($image_path);
        // }
        // delete favourites
        $favs = Favourite::where('post_id', $prod_req->id)->where('post_type', 1)->get();
        foreach ($favs as $fav) {
            $fav->delete();
        }
        $prod_req->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Request Deleted Successfully'
        ], 200);
    }

    public function addInformation($prod_reqs)
    {
        foreach ($prod_reqs as $prod_req) {
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
