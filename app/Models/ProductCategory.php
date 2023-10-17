<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    
    public function sales()
    {
        return $this->hasMany(SalesModel::class, 'id');
    }

    public function products() {
        return $this->hasMany(ProductsModel::class, 'product_category_id');
    }
}
