<?php

namespace App\Services;

use App\Models\AccountsModel;
use Illuminate\Support\Facades\Config;

class AccountsService
{
    private  $accountsModel;

    public function __construct()
    {
        $this->accountsModel = new AccountsModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->accountsModel->storeAccount($requestedData, $adminId);
    }

    public function update(int $accountId, array $requestedData)
    {
        return $this->accountsModel->updateAccount($accountId, $requestedData);
    }

    public function loadCalculateNetAndVatByGivenGross($gross)
    {
        $getTaxValueFromConfig = Config::get('crm_settings.invoice_tax')  / 100;
        $getGrossValueFromInput = $gross;

        $vat = $getGrossValueFromInput * $getTaxValueFromConfig;

        $net = $getGrossValueFromInput - $vat;

        return $result = [
            'net' => $net,
            'vat' => $vat,
        ];
    }

    public function loadAccounts()
    {
        return $this->accountsModel->getAccountsSortedByCreatedAt();
    }

    public function loadPagination()
    {
        return $this->accountsModel->getPaginate();
    }

    public function loadAccount(int $accountId)
    {
        return $this->accountsModel::find($accountId);
    }

    public function loadIsActive(int $accountId, int $value)
    {
        return $this->accountsModel->setActive($accountId, $value);
    }

    public function loadCountAccounts()
    {
        return $this->accountsModel->countAccounts();
    }
}
