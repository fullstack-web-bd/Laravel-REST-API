<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'image',
        'user_id',
    ];

    protected $appends = ['image_url'];
    protected $hidden = ['image'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        return url('/') . Storage::url($this->image);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
