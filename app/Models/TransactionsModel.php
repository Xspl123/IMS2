<?php

namespace App\Models;

use App\Services\TransactionsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class TransactionsModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'transactions';

    public function categoryData()
    {
        return $this->belongsTo(Category::class, 'category');
    }
    public function pmethodData()
    {
        return $this->belongsTo(SysPMethod::class, 'method');
    }

    public function payerData()    
    {
        return $this->belongsTo(ClientsModel::class, 'payerid', 'id');
    }

    public function payeeData()    
    {
        return $this->belongsTo(VendorModel::class, 'payeeid', 'id');
    }

    public function companies()
    {
        return $this->belongsTo(CompaniesModel::class);
    }

    public function accountData()
    {
        return $this->belongsTo(AccountsModel::class,'account');
    }

    public function storeTransaction(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();
        $requestedData['admin_id'] = $adminId;
    
        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    
        return $this->insertGetId($data);
    }

    public function updateTransaction(int $transactionId, array $requestedData): int
    {
        // Perform the update operation using the extracted data
        return $this->where('id', $transactionId)->update($requestedData);
    }

    public function setActive(int $transactionId, int $activeType) : int
    {
        return $this->where('id', '=', $transactionId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countTransactions() : int
    {
        return $this->get()->count();
    }

    public function getTransactionsSortedByCreatedAt()
    {
        return $this->all()->sortByDesc('created_at');
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
}
