<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    use HasFactory;
    protected $fillable = [
        'cetegory_id', // Include 'category_id' here if you're using $fillable
        'title',
        'country',
        'auther',
        'publish',
        'description',
        'content',
        'image',
        'url',
        // Add other fillable fields here
    ];
}
