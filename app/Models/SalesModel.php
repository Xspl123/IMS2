<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SalesModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'replace_remark',
        'replacement_with',
        'replacement_to',
        'sn',
        'replacement_product_sn',
        'approved_by'
        
    ];

    protected $table = 'sales';
    protected $dates = ['deleted_at'];

    public function custmorData()
    {
        return $this->belongsTo(ClientsModel::class,'client_id');
    }

    public function vendorData()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id');
    }


    public function products()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }

    // public function storeSale(array $requestedData, int $adminId) : int
    // {
    //     return $this->insertGetId(
    //         [
    //             'name' => $requestedData['name'],
    //             'quantity' => $requestedData['quantity'],
    //             'date_of_payment' => $requestedData['date_of_payment'],
    //             'product_id' => $requestedData['product_id'],
    //             'price' => $requestedData['price'],
    //             'total_amount' => $requestedData['total_amount'],
    //             'created_at' => now(),
    //             'is_active' => true,
    //             'admin_id' => $adminId
    //         ]
    //     );
    // }

    public function storeSale(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();
        $requestedData['admin_id'] = $adminId; // Add admin_id to the requested data
    
        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    
        return $this->insertGetId($data);
    }
    

    public function updateSale(int $saleId, array $requestedData) : int
    {
        return $this->where('id', '=', $saleId)->update(
            [
                'name' => $requestedData['name'],
                'quantity' => $requestedData['quantity'],
                'date_of_payment' => $requestedData['date_of_payment'],
                'product_id' => $requestedData['product_id'],
                'updated_at' => now()
            ]
        );
    }

    public function setActive(int $saleId, int $activeType) : int
    {
        return $this->where('id', '=', $saleId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countSales() : int
    {
        return $this->all()->count();
    }

    public function getPaginate()
    {
        return $this->paginate(SettingsModel::where('key', 'pagination_size')->get()->last()->value);
    }

    public function getSalesSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getSale(int $saleId) : self
    {
        return $this->find($saleId);
    }
}
