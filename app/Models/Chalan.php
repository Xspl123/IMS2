<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chalan extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'sale_id',
        'sale_id',
        'replacement_Remark',
        'replacement_product_item',
        'replacement_to_custmor',
        'replacement_product_serial',
        'replacement_product_vendor',
        'defulty_product_name',
        'defulty_product_sn',
        'defulty_product_vendor',
        'defulty_product_remark',
        'approved_by',
        'challan_no',
        'created_at',
        'updated_at'
        
    ];

    public function custmorData()
    {
        return $this->belongsTo(ClientsModel::class,'replacement_to_custmor');
    }

    public function vendorData()
    {
        return $this->belongsTo(VendorModel::class, 'replacement_product_vendor');
    }
    public function DefultyvendorData()
    {
        return $this->belongsTo(VendorModel::class, 'defulty_product_vendor');
    }
    

    public function products()
    {
        return $this->belongsTo(ProductsModel::class, 'replacement_product_item');
    }
    public function productsR()
    {
        return $this->belongsTo(ProductsModel::class, 'replacement_to');
    }
}
