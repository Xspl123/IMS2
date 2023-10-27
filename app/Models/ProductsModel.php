<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Arr;
use Config;

class ProductsModel extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $fillable = [
        'barcode',
        'name',
        'description',
        'brand_name',
        'price_with_gst',
        'count',
        'price',
        'category',
        'gstAmount',
        'rent_start_date',
        'rent_end_date',
        'created_at',
        'updated_at',
        'is_active',
        'admin_id',
        'vendor_id',
        'product_serial_no',
        'product_type'
    ];

    public function barcode()
    {
        return $this->hasOne(Barcode::class);
    }
    public function vendor()
    {
        return $this->belongsTo(VendorModel::class);
    }

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }

    public function sales()
    {
        return $this->hasMany(SalesModel::class, 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function storeProduct(array $requestedData, int $adminId): int
    {

        $data = [
            'barcode' => $requestedData['barcode'],
            'product_serial_no' => $requestedData['product_serial_no'],
            'mac_address' => $requestedData['mac_address'],
            'name' => $requestedData['name'],
            'product_category_id' => $requestedData['catgory_name'],
            'description' => $requestedData['description'],
            'brand_name' => $requestedData['brand_name'],
            'price_with_gst' => $requestedData['price_with_gst'],
            'gst_rate' => $requestedData['gst_rate'],
            'price' => $requestedData['price'],
            'gstAmount' => $requestedData['gstAmount'],
            'total_amount' => $requestedData['total_amount'],
            'created_at' => now(),
            'updated_at' => now(),
            'is_active' => true,
            'admin_id' => $adminId,
            'vendor_id' => $requestedData['vendor_id'],
            'rent_start_date' => $requestedData['rent_start_date'],
            'rent_end_date' => $requestedData['rent_end_date'],
            'product_type' => $requestedData['product_type'],

        ];
       
        return $this->insertGetId($data);
    }





    public function updateProduct(int $productId, array $requestedData): int
    {
        $data = [
            'name' => $requestedData['name'],
            'description' => $requestedData['description'],
            'mac_address' => $requestedData['mac_address'],
            'brand_name' => $requestedData['brand_name'],
            'count' => $requestedData['count'],
            'price' => $requestedData['price'],
            'rented' => isset($requestedData['rented']) ? $requestedData['rented'] : 0,
            'rent_start_date' => isset($requestedData['rent_start_date']) ? $requestedData['rent_start_date'] : null,
            'rent_end_date' => isset($requestedData['rent_end_date']) ? $requestedData['rent_end_date'] : null,
            'updated_at' => now()
        ];

        if ($requestedData['rented'] != '1') {
            $data['rent_start_date'] = null;
            $data['rent_end_date'] = null;
        }

        return $this->where('id', '=', $productId)->update($data);
       }


        public function setActive(int $productId, int $activeType) : int
        {
            return $this->where('id', '=', $productId)->update(
                [
                    'is_active' => $activeType,
                    'updated_at' => now()
                ]
            );
        }

    public function countProducts() : int
    {
        return $this->get()->count();
    }

    public function getProductsByCreatedAt()
    {
        return $this->all()->sortBy('created_at', 0, true)->slice(0, 5);
    }

    public function findClientByGivenClientId(int $productId)
    {
        $query = $this->find($productId);

        Arr::add($query, 'salesCount', count($query->sales));

        return $query;
    }

    public function getProducts()
    {
        return $this->orderByDesc('created_at')->get();
    }
    

    public function getPaginate()
    {
        return $this->paginate(SettingsModel::where('key', 'pagination_size')->get()->last()->value);
    }

    public function getProduct(int $productId) : self
    {
        return $this->find($productId);
    }
}
