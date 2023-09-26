<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    protected $fillable = ['sale_id','rent_id', 'purchase_id', 'invoice_number', 'invoice_date', 'customer_details', 'subtotal', 'tax', 'grand_total'];

    use HasFactory;
}
