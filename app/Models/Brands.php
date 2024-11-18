<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brands extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function modelslist()
    {
        return $this->hasMany(Models::class, 'brand_id', 'id');
    }
}
