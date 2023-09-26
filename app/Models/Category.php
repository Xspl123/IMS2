<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function transactions()
    {
        return $this->hasMany(TransactionsModel::class, 'category_id');
    }
}
