<?php

namespace App\Services;

use App\Models\ProductsModel;
use App\Models\RentsModel;

class RentsService
{
    private $rentsModel;
    protected $table = 'rents'; // Replace with your actual table name

    public function __construct()
    {
        $this->rentsModel = new RentsModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->rentsModel->storeRent($requestedData, $adminId);
    }

    public function find($rentId)
    {
        return $this->rentsModel->find($rentId);
    }

    public function updateRent(int $rentId, array $data)
    {
        $rent = $this->find($rentId);

        if (!$rent) {
            throw new \Exception('Rent not found.');
        }

        $rent->fill($data);
        $rent->save();

        return $rent;
    }

    public function loadRents()
    {
        return $this->rentsModel->getRentsSortedByCreatedAt();
    }

    public function loadPaginate()
    {
        return $this->rentsModel->getPaginate();
    }

    public function loadRent(int $rentId)
    {
        return $this->rentsModel->getRent($rentId);
    }

    public function loadIsActive(int $rentId, int $value)
    {
        return $this->rentsModel->setActive($rentId, $value);
    }

    public function loadCountRents()
    {
        return $this->rentsModel->countRents();
    }
}
