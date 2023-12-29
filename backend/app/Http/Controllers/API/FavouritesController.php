<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductPost;
use App\Models\ProductRequest;

class FavouritesController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function favUnfavPost($post_id)
    {

        $post = ProductPost::find($post_id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ]);
        }
        // if already in favourites of the user, then remove from favourites
        $fav = Favourite::where('post_id', $post_id)->where('user_id', Auth::user()->id)->where('post_type', 0)->first();
        if ($fav) {
            // delete from favourites
            $fav->delete();
            return response()->json([
                'success' => true,
                'message' => 'Post removed from favourites successfully'
            ]);
        }

        // if not in favourites, then add to favourites
        $fav = new Favourite();
        $fav->user_id = Auth::user()->id;
        $fav->post_type = 0;
        $fav->post_id = $post_id;
        $fav->save();

        return response()->json([
            'success' => true,
            'message' => 'Post added to favourites successfully'
        ]);
    }

    public function favUnfavRequest($prod_req_id)
    {

        $prod_req = ProductRequest::find($prod_req_id);
        if (!$prod_req) {
            return response()->json([
                'success' => false,
                'message' => 'Product Request not found'
            ]);
        }
        // if already in favourites of the user, then remove from favourites
        $fav = Favourite::where('post_id', $prod_req_id)->where('user_id', Auth::user()->id)->where('post_type', 1)->first();
        if ($fav) {
            // delete from favourites
            $fav->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product Request removed from favourites successfully'
            ]);
        }

        // if not in favourites, then add to favourites
        $fav = new Favourite();
        $fav->user_id = Auth::user()->id;
        $fav->post_type = 1;
        $fav->post_id = $prod_req_id;
        $fav->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Request added to favourites successfully'
        ]);
    }
    public function getFavourites()
    {
        // $favourites = Favourite::where('user_id', Auth::user()->id)->get(); in desc order
        $favourites = Favourite::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        // add favourite->getPost to each
        foreach($favourites as $fav){
            $content = $fav->getPost;
            $content->favStatus = true;
            $fav->content = $content;
            $fav->post_type = $fav->post_type == 0 ? 'prod_post' : 'prod_request';
        }
        // $posts = [];
        // $requests = [];
        // foreach ($favourites as $fav) {
        //     if ($fav->post_type == 0) {
        //         $post = ProductPost::find($fav->post_id);
        //         if ($post) {
        //             array_push($posts, $post);
        //         }
        //     } else {
        //         $request = ProductRequest::find($fav->post_id);
        //         if ($request) {
        //             array_push($requests, $request);
        //         }
        //     }
        // }
        return response()->json([
            'success' => true,
            'favourites' => $favourites,
        ]);
    }
}
