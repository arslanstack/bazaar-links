<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductPost;
use Illuminate\Support\Facades\Validator;

class ProductPostController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductPost::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('title', 'like', '%' . $search_query . '%')
                    ->orWhereHas('Vendor', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
        }
        $data['posts'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin.posts.manage_posts', $data);
    }
    public function update_statuses(Request $request)
    {
        $data = $request->all();
        $status = ProductPost::where('id', $data['id'])->update([
            'status' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if ($status > 0) {
            if ($data['status'] == '1') {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Product Post Enabled successfully."]);
            } else {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Product Post Disabled successfully."]);
            }
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }
    public function post_details($id)
    {
        $post = ProductPost::where('id', $id)->first();

        if (!empty($post)) {
            $post = $this->addInformation($post);
            return view('admin/posts/post_details', compact('post'));
        }

        return view('common/admin_404');
    }
    public function addInformation($post)
    {
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

        return $post;
    }
}
