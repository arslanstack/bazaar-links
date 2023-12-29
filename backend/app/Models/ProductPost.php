<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductPost extends Model
{
    use HasFactory;

    protected $table = 'product_posts';

    protected $fillable = [
        'title',
        'product_location',
        'unit_id',
        'price',
        'quantity',
        'category_id',
        'subcategory_id',
        'moisture',
        'place_of_origin',
        'brand',
        'model_no',
        'certification',
        'description',
        'status',
        'vendor_id',
        'vendor_location',
    ];

    public function Productcity()
    {
        return $this->belongsTo(City::class, 'product_location');
    }
    public function Vendorcity()
    {
        return $this->belongsTo(City::class, 'vendor_location');
    }
    public function Vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
    public function Unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function SubCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }
    public function FavStatus()
    {
        $token = request()->header('Authorization');
        if ($token) {
            try {
                $user = JWTAuth::parseToken()->authenticate();
                $fav = Favourite::where('post_id', $this->id)->where('user_id', $user->id)->where('post_type', 0)->first();
                if ($fav) {
                    return true;
                }
            } catch (JWTException $e) {
            }
        }
        return false;
    }
}
