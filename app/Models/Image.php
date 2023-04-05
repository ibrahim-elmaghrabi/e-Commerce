<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $guarded = [] ;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
