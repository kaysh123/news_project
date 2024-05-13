<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiSelect extends Model
{
    protected $table = 'apiselect';
    protected $fillable = ['api_name', 'api_key',];
    use HasFactory;
}
