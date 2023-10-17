<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ClientsModel extends Model
{
    use SoftDeletes;

    protected $table = 'clients';
    protected $dates = ['deleted_at'];

    public function transactions()
    {
        return $this->hasMany(TransactionsModel::class, 'payerid', 'id');
    }

    public function companies()
    {
        return $this->hasMany(CompaniesModel::class, 'id');
    }

    public function employees()
    {
        return $this->hasMany(EmployeesModel::class, 'id');
    }

    public function storeClient(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'full_name' => $requestedData['full_name'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'section' => $requestedData['section'],
                // 'budget' => $requestedData['budget'],
                'location' => $requestedData['location'],
                'zip' => $requestedData['zip'],
                'city' => $requestedData['city'],
                'country' => $requestedData['country'],
                'created_at' => now(),
                'is_active' => true,
                'admin_id' => $adminId
            ]
        );
    }

    public function updateClient(int $id, array $requestedData) : int
    {
        return $this->where('id', '=', $id)->update(
            [
                'full_name' => $requestedData['full_name'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'section' => $requestedData['section'],
                // 'budget' => $requestedData['budget'],
                'location' => $requestedData['location'],
                'zip' => $requestedData['zip'],
                'city' => $requestedData['city'],
                'country' => $requestedData['country'],
                'updated_at' => now()
            ]
        );
    }

    public function setClientActive(int $id, int $activeType) : int
    {
        return $this->where('id', '=', $id)->update(
            [
                'is_active' => $activeType,
                'updated_at' => now()
            ]
        );
    }

    public function countClients() : int
    {
        return $this->all()->count();
    }

    public static function getClientsInLatestMonth() : float
    {
        $clientCount = self::where('created_at', '>=', now()->subMonth())->count();
        $allClient = self::all()->count();

        return ($allClient / 100) * $clientCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getClientByGivenClientId(int $clientId) : self
    {
        try {
            $query = $this->findOrFail($clientId);
        } catch (ModelNotFoundException $exception) {
            throw new NotFoundHttpException('User with the given clientId does not exist.');
        }

        Arr::add($query, 'companiesCount', count($query->companies));
        Arr::add($query, 'employeesCount', count($query->employees));
        Arr::add($query, 'formattedBudget', Money::{SettingsModel::getSettingValue('currency')}($query->budget));

        return $query;
    }

//     public function getClientByGivenClientId(int $clientId) : self
// {
//     $query = $this->find($clientId);

//     if (is_null($query)) {
//         throw new NotFoundHttpException('User with the given clientId does not exist.');
//     }

//     Arr::add($query, 'companiesCount', count($query->companies));
//     Arr::add($query, 'employeesCount', count($query->employees));
//     Arr::add($query, 'formattedBudget', Money::{SettingsModel::getSettingValue('currency')}($query->budget));

//     return $query;
// }

    public function getClientSortedBy($createForm = false)
    {
        if($createForm) {
            return $this->pluck('full_name', 'id');
        }

        $query = $this->all()->sortBy('created_at');

        foreach($query as $key => $client) {
            $query[$key]->budget = Money::{SettingsModel::getSettingValue('currency')}($client->budget);
        }

        return $query;
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

    public function deleteClient(int $clientId)
    {
        return $this->where('id', $clientId)->delete();
    }
}
