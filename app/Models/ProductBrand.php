<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;


    public static function getbrands()
    {
        return ProductBrand::get();

    } 
    
    public function sales()
    {
        return $this->hasMany(SalesModel::class, 'id');
    }


}


