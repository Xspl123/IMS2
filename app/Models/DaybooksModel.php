<?php

namespace App\Models;

use App\Services\DaybooksService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class DaybooksModel extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'daybooks';

    public function categoryData()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category');
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

    public function storeDaybook(array $requestedData, int $adminId): int
    {
        $now = Carbon::now();
        $requestedData['admin_id'] = $adminId; // Add admin_id to the requested data
    
        $data = array_merge($requestedData, [
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    
        return $this->insertGetId($data);
    }

    public function updateDaybook(int $daybookId, array $requestedData): int
    {
        // Perform the update operation using the extracted data
        return $this->where('id', $daybookId)->update($requestedData);
    }

    public function setActive(int $daybookId, int $activeType) : int
    {
        return $this->where('id', '=', $daybookId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countDaybooks() : int
    {
        return $this->get()->count();
    }

    public function getDaybooksSortedByCreatedAt()
    {
        return $this->all()->sortByDesc('created_at');
    }

    

    public function getPaginate()
    {
        return $this->paginate(SettingsModel::where('key', 'pagination_size')->get()->last()->value);
    }
}
