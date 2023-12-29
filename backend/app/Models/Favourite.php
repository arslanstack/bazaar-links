<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $table = 'favourites';

    protected $fillable = [
        'user_id',
        'post_type',
        'post_id',
    ];  

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getPost()
    {
        if ($this->post_type === 0) {
            return $this->belongsTo(ProductPost::class, 'post_id');
        } elseif ($this->post_type === 1) {
            return $this->belongsTo(ProductRequest::class, 'post_id');
        }
    }
}
