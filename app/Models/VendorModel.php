<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class VendorModel extends Model
{
    use SoftDeletes;

    protected $table = 'vendors';
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(SalesModel::class);
    }

    public function client()
    {
        return $this->belongsTo(ClientsModel::class);
    }

    public function deals()
    {
        return $this->hasMany(DealsModel::class, 'id');
    }

    public function employees_size()
    {
        return $this->belongsTo(EmployeesModel::class);
    }

    public function finances()
    {
        return $this->hasMany(FinancesModel::class);
    }

    public function storeVendor(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'name' => $requestedData['name'],
                'gst_number' => $requestedData['gst_number'],
                'phone' => $requestedData['phone'],
                'city' => $requestedData['city'],
                'billing_address' => $requestedData['billing_address'],
                'country' => $requestedData['country'],
                'postal_code' => $requestedData['postal_code'],
                'created_at' => now(),
                'is_active' => true,
                'admin_id' => $adminId
            ]
        );
    }

    public function updateVendor(int $vendorsId, array $requestedData) : int
    {
        return $this->where('id', '=', $vendorsId)->update(
            [
                'name' => $requestedData['name'],
                'gst_number' => $requestedData['gst_number'],
                'phone' => $requestedData['phone'],
                'city' => $requestedData['city'],
                'billing_address' => $requestedData['billing_address'],
                'country' => $requestedData['country'],
                'postal_code' => $requestedData['postal_code'],
                'client_id' => $requestedData['client_id'],
                'is_active' => true,
                'updated_at' => now()
            ]);
    }

    public function setActive(int $vendorsId, int $activeType) : int
    {
       return $this->where('id', '=', $vendorsId)->update(
           [
               'is_active' => $activeType
           ]
       );
    }

    public function countVendors() : int
    {
        return $this->all()->count();
    }

    public function getVendorsInLatestMonth() : float
    {
        $vendorsCount = $this->where('created_at', '>=', now()->subMonth())->count();
        $allVendors = $this->all()->count();

        return ($allVendors / 100) * $vendorsCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getVendorsSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at', 0, true)->slice(0, 5);
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


    public function getVendor(int $vendorId)
    {
        return $this::find($vendorId);
    }

    public function pluckData()
    {
        return $this->pluck('name', 'id');
    }

    public function getAll($createForm = false)
    {
        if($createForm) {
            return $this->pluck('name', 'id');
        }

        return $this->all()->sortBy('created_at');
    }
}
