<?php

namespace App\Models\Cetegory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cetegory extends Model
{
    public function news()
    {
        return $this->hasMany('App\News');
    }
    protected $table = 'categories'; 
    use HasFactory;
}
