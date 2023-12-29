<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::where('status', 1)->whereNull('parent_id')->get();
        foreach ($categories as $category) {
            if ($category->image) {
                $category->image = asset('uploads/categories/' . $category->image);
            }
        }
        return response()->json(['msg' => 'success', 'response' => $categories]);
    }
    public function show_category($id)
    {
        $category = Category::where('status', 1)->where('id', $id)->first();
        if ($category->image) {
            $category->image = asset('uploads/categories/' . $category->image);
        }
        $subcategories = Category::where('status', 1)->where('parent_id', $id)->get();
        foreach ($subcategories as $subcategory) {
            if ($subcategory->image) {
                $subcategory->image = asset('uploads/categories/' . $subcategory->image);
            }
        }
        return response()->json(['msg' => 'success', 'response' => ['category' => $category, 'subcategories' => $subcategories]]);
    }
    public function all_subcategories()
    {
        $subcategories = Category::where('status', 1)->whereNotNull('parent_id')->get();
        foreach ($subcategories as $subcategory) {
            if ($subcategory->image) {
                $subcategory->image = asset('uploads/categories/' . $subcategory->image);
            }
        }
        return response()->json(['msg' => 'success', 'response' => $subcategories]);
    }
    public function specific_subcategories($parent_id)
    {
        $subcategories = Category::where('status', 1)->where('parent_id', $parent_id)->get();
        foreach ($subcategories as $subcategory) {
            if ($subcategory->image) {
                $subcategory->image = asset('uploads/categories/' . $subcategory->image);
            }
        }
        return response()->json(['msg' => 'success', 'response' => $subcategories]);
    }
}
