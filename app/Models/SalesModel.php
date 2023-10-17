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
        'product_id',
        'replace_remark',
        'replacement_with',
        'replacement_to',
        'sn',
        'replacement_product_sn',
        'replacement_product_vendor',
        'defulty_product_name',
        'defulty_product_sn',
        'defulty_product_vendor',
        'defulty_product_remark',
        'approved_by',
        'product_brand_id',
        'brand_name',
        'cat_id'
        
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


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'cat_id');
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
    

    // public function updateSale(int $saleId, array $requestedData) : int
    // {
        
    //     return $this->where('id', '=', $saleId)->update(
    //         [
    //             'name' => $requestedData['name'],
    //             'date_of_payment' => $requestedData['date_of_payment'],
    //             'product_id' => $requestedData['product_id'],
    //             'replacement_product_vendor'=> $requestedData['replacement_product_vendor'],
    //             'defulty_product_vendor'=> $requestedData['defulty_product_vendor'],
    //             'updated_at' => now()
    //         ]
    //     );
    // }

    public function updateSale(int $saleId, array $requestedData): int
    {
        dd($requestedData->all());
        // Add 'updated_at' timestamp
        $requestedData['updated_at'] = now();

        // Perform the database update
        return $this->where('id', '=', $saleId)->update($requestedData);
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
        $settings = SettingsModel::where('key', 'pagination_size')->latest('created_at')->first();

        if ($settings && !empty($settings->value)) {
            return $this->paginate($settings->value);
        } else {
            // Provide a default pagination size or handle the case when the value is empty
            return $this->paginate(10); // You can replace 10 with your desired default pagination size
        }
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
