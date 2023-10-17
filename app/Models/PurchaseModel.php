<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PurchaseModel extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'product_id', 'quantity', 'date_of_payment', 'price', 'gst_rate', 'total_price', 'admin_id'];


    protected $table = 'purchases';
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->belongsTo(ProductsModel::class, 'product_id');
    }

    public function storePurchases(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();

        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $this->insertGetId($data);
    }


    public function updatePurchases(int $purchasesId, array $requestedData) : int
    {
        return $this->where('id', '=', $purchasesId)->update(
            [
                'name' => $requestedData['name'],
                'quantity' => $requestedData['quantity'],
                'date_of_payment' => $requestedData['date_of_payment'],
                'product_id' => $requestedData['product_id'],
                'updated_at' => now()
            ]
        );
    }

    public function setActive(int $purchaseId, int $activeType) : int
    {
        return $this->where('id', '=', $purchaseId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countPurchases() : int
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

    public function getPurchasesSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getPurchases(int $purchaseId) : self
    {
        return $this->find($purchaseId);
    }
}
