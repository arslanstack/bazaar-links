<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        $search_query = $request->input('search_query');
        if ($request->has('search_query') && !empty($search_query)) {
            $query->where(function ($query) use ($search_query) {
                $query->where('title', 'like', '%' . $search_query . '%')
                ;
            });
        }
        $query->whereNull('parent_id');
        $data['categories'] = $query->orderBy('id', 'DESC')->paginate(50);
        $data['searchParams'] = $request->all();
        return view('admin/categories/manage_categories', $data);
    }

    public function category_details($id)
    {
        $category = Category::where('id', $id)->first();

        if (!empty($category)) {
            $subcategories = $category->subcategories;
            return view('admin/categories/category_details', compact('category', 'subcategories'));
        }

        return view('common/admin_404');
    }

    public function update_statuses(Request $request)
    {
        $data = $request->all();
        $status = Category::where('id', $data['id'])->update([
            'status' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // change status of all subcategories of this category
        $subcategories = Category::where('parent_id', $data['id'])->get();
        foreach ($subcategories as $subcategory) {
            Category::where('id', $subcategory->id)->update([
                'status' => $data['status'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $subcategories_count = Category::where('id', $data['id'])->count();
        if ($status > 0) {
            if ($data['status'] == '1') {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Category and it's " . $subcategories_count . " sub-categories Enabled successfully."]);
            } else {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Category and it's " . $subcategories_count . " sub-categories Disabled successfully."]);
            }
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }

    public function show_add_category()
    {
        return view('admin/categories/add_category');
    }

    public function store_category(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => 'Invaid Category Title.'));
        }
        // if category with title already exists then return error
        $category = Category::where('title', $data['title'])->first();
        if (!empty($category)) {
            return response()->json(array('msg' => 'error', 'response' => 'Category already exists.'));
        }
        $category = new Category();
        $category->title = $data['title'];
        $category->status = 1;
        $category->save();
        if ($category->id > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => 'Category added successfully.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }
    public function update_category(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => 'Invalid Category Title.'));
        }

        $category = Category::where('title', $data['title'])->first();

        if (!empty($category) && $category->id != $data['id']) {
            return response()->json(array('msg' => 'error', 'response' => 'Category ' . $category->title . ' already exists.'));
        }

        if (isset($request->status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        $query = Category::where('id', $data['id'])->update([
            'title' => $data['title'],
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // update status for all subcategoies as well
        $subcategories = Category::where('parent_id', $data['id'])->get();
        foreach ($subcategories as $subcategory) {
            Category::where('id', $subcategory->id)->update([
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($query > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => 'Category updated successfully.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }

    public function delete_category(Request $request){
        $data = $request->all();
        $status = Category::where('id', $data['id'])->first();
        // if category has subcategories then return error
        $subcategories = Category::where('parent_id', $data['id'])->get();
        if (count($subcategories) > 0) {
            return response()->json(array('msg' => 'error', 'response' => 'Category has sub-categories. Please delete them first.'));
        }
        $status = Category::where('id', $data['id'])->delete();
        if ($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => "Category Deleted successfully."]);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }

    public function update_subcategory_status(Request $request)
    {
        $data = $request->all();
        $status = Category::where('id', $data['id'])->update([
            'status' => $data['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if ($status > 0) {
            if ($data['status'] == '1') {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Sub-Category Enabled successfully."]);
            } else {
                $finalResult = response()->json(['msg' => 'success', 'response' => "Sub-Category Disabled successfully."]);
            }
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }
    public function delete_subcategory(Request $request)
    {
        $data = $request->all();
        $status = Category::where('id', $data['id'])->delete();
        if ($status > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => "Sub-Category Deleted successfully."]);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }

    public function subcategory_show(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        $subcat = Category::where('id', $id)->first();
        $htmlresult = view('admin/categories/edit_subcategory_ajax', compact('subcat'))->render();
        $finalResult = response()->json(['msg' => 'success', 'response' => $htmlresult]);
        return $finalResult;
    }

    public function update_subcategory(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => 'Invalid Sub-Category Title.'));
        }

        $category = Category::where('title', $data['title'])->first();

        if (!empty($category) && $category->id != $data['id']) {
            return response()->json(array('msg' => 'error', 'response' => 'Sub-Category or Category with title ' . $category->title . ' already exists.'));
        }
        $query = Category::where('id', $data['id'])->update([
            'title' => $data['title'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // if request has file image 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/categories/');
            $image->move($destinationPath, $image_name);
            $querytwo = Category::where('id', $data['id'])->update([
                'image' => $image_name,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($query > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => 'Sub-Category updated successfully.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }

    public function store_subcategory(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => 'Invalid Sub-Category Title.'));
        }

        $category = Category::where('title', $data['title'])->first();

        if (!empty($category)) {
            return response()->json(array('msg' => 'error', 'response' => 'Sub-Category or Category with title ' . $category->title . ' already exists.'));
        }
        $query = Category::create([
            'title' => $data['title'],
            'parent_id' => $data['category_id'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        // if request has file image 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/categories/');
            $image->move($destinationPath, $image_name);
            $querytwo = Category::where('id', $query->id)->update([
                'image' => $image_name,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        if ($query->id > 0) {
            $finalResult = response()->json(['msg' => 'success', 'response' => 'Sub-Category added successfully.']);
            return $finalResult;
        } else {
            $finalResult = response()->json(['msg' => 'error', 'response' => 'Something went wrong!']);
            return $finalResult;
        }
    }
}
