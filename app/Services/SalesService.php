<?php

namespace App\Services;

use App\Models\ProductsModel;
use App\Models\SalesModel;

class SalesService
{
    private $salesModel;
    protected $table = 'sales'; // Replace with your actual table name

    public function __construct()
    {
        $this->salesModel = new SalesModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->salesModel->storeSale($requestedData, $adminId);
    }

    public function find($saleId)
    {
        return $this->salesModel->find($saleId);
    }

    public function updateSale(int $saleId, array $data)
    {
      // dd($data);
        $sale = $this->find($saleId);
       
        if (!$sale) {
            throw new \Exception('Sale not found.');
        }

        $sale->fill($data);
        $sale->save();

        return $sale;
    }

    public function loadSales()
    {
        return $this->salesModel->getSalesSortedByCreatedAt();
    }

    public function loadPaginate()
    {
        return $this->salesModel->getPaginate();
    }

    public function loadSale(int $saleId)
    {
        return $this->salesModel->getSale($saleId);
    }

    public function loadIsActive(int $saleId, int $value)
    {
        return $this->salesModel->setActive($saleId, $value);
    }

    public function loadCountSales()
    {
        return $this->salesModel->countSales();
    }
}
