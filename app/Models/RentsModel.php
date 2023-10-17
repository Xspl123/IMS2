<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RentsModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $table = 'rents';
    protected $dates = ['deleted_at'];

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

    public function storeRent(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();
        $requestedData['admin_id'] = $adminId; // Add admin_id to the requested data
    
        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    
        return $this->insertGetId($data);
    }
    

    public function updateRent(int $rentId, array $requestedData) : int
    {
        return $this->where('id', '=', $rentId)->update(
            [
                'name' => $requestedData['name'],
                'quantity' => $requestedData['quantity'],
                'date_of_payment' => $requestedData['date_of_payment'],
                'product_id' => $requestedData['product_id'],
                'updated_at' => now()
            ]
        );
    }

    public function setActive(int $rentId, int $activeType) : int
    {
        return $this->where('id', '=', $rentId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countRents() : int
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

    public function getRentsSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getRent(int $rentId) : self
    {
        return $this->find($rentId);
    }
}
