<?php

namespace App\Services;

use App\Models\ProductsModel;
use App\Models\PurchaseModel;

class PurchaseService
{
    private $PurchaseModel;
    protected $table = 'sales'; // Replace with your actual table name

    public function __construct()
    {
        $this->PurchaseModel = new PurchaseModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->PurchaseModel->storePurchases($requestedData, $adminId);
    }


    public function find($purchasId)
    {
        return $this->PurchaseModel->find($purchasId);
    }

    public function updatePurchase(int $purchaseId, array $data)
    {
        $purchase = $this->find($purchaseId);

        if (!$purchase) {
            throw new \Exception('Purchase not found.');
        }

        $purchase->fill($data);
        $purchase->save();

        return $purchase;
    }

    public function loadSales()
    {
        return $this->PurchaseModel->getPurchasesSortedByCreatedAt();
    }

    public function loadPaginate()
    {
        return $this->PurchaseModel->getPaginate();
    }

    public function loadPurchase(int $purchaseId)
    {
        return $this->PurchaseModel->getPurchases($purchaseId);
    }

    public function loadIsActive(int $purchasId, int $value)
    {
        return $this->PurchaseModel->setActive($purchasId, $value);
    }

    public function loadCountPurchases()
    {
        return $this->PurchaseModel->countPurchases();
    }
}
