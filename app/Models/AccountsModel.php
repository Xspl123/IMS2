<?php

namespace App\Models;

use App\Services\AccountsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class AccountsModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'accounts';
     
    
    public function companies()
    {
        return $this->belongsTo(CompaniesModel::class);
        
    }

    public function transactions()
    {
        return $this->hasMany(TransactionsModel::class);
    }

    public function storeAccount(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();
        $requestedData['admin_id'] = $adminId; // Add admin_id to the requested data
    
        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    
        return $this->insertGetId($data);
    }

    // public function updateAccount(int $accountId, array $requestedData) : int
    // {
        
    //     return $this->where('id', $accounteId)->update(
    //         [
    //             'name' => $requestedData['name'],
    //             'description' => $requestedData['description'],
    //             'type' => $requestedData['type'] ?? null,
    //             'category' => $requestedData['category'],
    //             'gross' => $requestedData['gross'],
    //             'net' => $dataToInsert['net'],
    //             'vat' => $dataToInsert['vat'],
    //             'date' => $requestedData['date'],
    //             'companies_id' => $requestedData['companies_id'],
    //             'updated_at' => now(),
    //             'is_active' => 1
    //         ]
    //     );
    // }

    public function updateAccount(int $accountId, array $requestedData): int
    {
        // Extract the specific fields from the $requestedData array
        $dataToUpdate = [
            'account' => $requestedData['account'] ?? null,
            'description' => $requestedData['description'] ?? null,
            'balance' => $requestedData['balance'] ?? null,
            'bank_name' => $requestedData['bank_name'] ?? null,
            'account_number' => $requestedData['account_number'] ?? null,
            'currency' => $requestedData['currency'] ?? null,
            'branch' => $requestedData['branch'] ?? null,
            'address' => $requestedData['address'] ?? null,
            'contact_person' => $requestedData['contact_person'] ?? null,
            'contact_phone' => $requestedData['contact_phone'] ?? null,
            'website' => $requestedData['website'] ?? null,
            'ib_url' => $requestedData['ib_url'] ?? null,
            'created' => $requestedData['created'] ?? null,
            'notes' => $requestedData['notes'] ?? null,
            'sorder' => $requestedData['sorder'] ?? null,
            'e' => $requestedData['e'] ?? null,
            'token' => $requestedData['token'] ?? null,
            'status' => $requestedData['status'] ?? null,
        ];

        // Perform the update operation using the extracted data
        return $this->where('id', $accountId)->update($dataToUpdate);
    }

    

    public function setActive(int $accountId, int $activeType) : int
    {
        return $this->where('id', '=', $accountId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countAccounts() : int
    {
        return $this->get()->count();
    }

    public function getAccountsSortedByCreatedAt()
    {
        return $this->all()->sortByDesc('created_at');
    }

    public function getPaginate()
    {
        return $this->paginate(SettingsModel::where('key', 'pagination_size')->get()->last()->value);
    }
}
