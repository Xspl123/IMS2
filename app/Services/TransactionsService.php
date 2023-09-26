<?php

namespace App\Services;
use DB;
use App\Models\TransactionsModel;
use Illuminate\Support\Facades\Config;

class TransactionsService
{
    private  $transactionsModel;

    public function __construct()
    {
        $this->transactionsModel = new TransactionsModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->transactionsModel->storeTransaction($requestedData, $adminId);
    }

    public function update(int $transactionId, array $requestedData)
    {
        return $this->transactionsModel->updateTransaction($transactionId, $requestedData);
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

    public function loadTransactions()
    {
        return $this->transactionsModel->getTransactionsSortedByCreatedAt()
                                    ->take(3);
                                    
    }


    public function getLatestDrValue()
    {
        $latestDrValue = TransactionsModel::orderBy('created_at', 'desc')
                                    
                                    ->value('dr');

        return $latestDrValue;
    }


    public function loadPagination()
    {
        return $this->transactionsModel->getPaginate();
    }

    public function loadTransaction(int $transactionId)
    {
        return $this->transactionsModel::find($transactionId);
    }

    public function loadIsActive(int $transactionId, int $value)
    {
        return $this->transactionsModel->setActive($transactionId, $value);
    }

    public function loadCountTransactions()
    {
        return $this->transactionsModel->countTransactions();
    }
}
