<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductRequest;
use Illuminate\Support\Facades\Validator;

class ProductRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductRequest::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('title', 'like', '%' . $search_query . '%')
                    ->orWhereHas('Vendor', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
        }
        $data['requests'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin.requests.manage_requests', $data);
    }
    public function update_statuses(Request $request)
    {
        $data = $request->all();
        $status = ProductRequest::where('id', $data['id'])->update([
            'status' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if ($status > 0) {
            if ($data['status'] == '1') {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Product Request Enabled successfully."]);
            } else {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Product Request Disabled successfully."]);
            }
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }
    public function prod_req_details($id)
    {
        $prod_req = ProductRequest::where('id', $id)->first();

        if (!empty($prod_req)) {
            $prod_req = $this->addInformation($prod_req);
            return view('admin/requests/prod_req_details', compact('prod_req'));
        }

        return view('common/admin_404');
    }
    public function addInformation($prod_req)
    {
        $vendor = $prod_req->Vendor;
        $vendor->city = $vendor->city->city_name;
        $prod_req->vendor = $vendor;
        $prod_req->product_location = $prod_req->Productcity->city_name;
        $prod_req->vendor_location = $prod_req->Vendorcity->city_name;
        $prod_req->unit = $prod_req->Unit->unit_name;
        $prod_req->category = $prod_req->Category->title;
        $prod_req->subcategory = $prod_req->SubCategory->title;
        return $prod_req;
    }
}
