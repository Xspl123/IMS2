<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    public function daybooks()
    {
        return $this->hasMany(ExpenseCategory::class, 'category');
    }
}
