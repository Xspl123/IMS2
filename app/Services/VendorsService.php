<?php

namespace App\Services;

use App\Models\VendorModel;

class VendorsService
{
    private $vendorModel;

    public function __construct(VendorModel $vendorModel)
    {
        $this->vendorModel = $vendorModel;
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->vendorModel->storeVendor($requestedData, $adminId);
    }

    public function update(int $vendorsId, array $requestedData)
    {
        return $this->vendorModel->updateVendor($vendorsId, $requestedData);
    }

    public function loadvendors($createForm = false)
    {
        return $this->vendorModel->getAll($createForm);
    }

    public function loadPagination()
    {
        return $this->vendorModel->getPaginate();
    }

    public function pluckData()
    {
        return $this->vendorModel->pluckData();
    }

    public function loadVendor(int $vendorId)
    {
        return $this->vendorModel->getVendor($vendorId);
    }

    public function loadSetActive(int $vendorsId, bool $value)
    {
        return $this->vendorModel->setActive($vendorsId, $value);
    }

    public function loadVendorsByCreatedAt()
    {
        return $this->vendorModel->getVendorsSortedByCreatedAt();
    }

    public function loadCountVendors()
    {
        return $this->vendorModel->countVendors();
    }

    public function loadDeactivatedVendors()
    {
        return $this->vendorModel->getDeactivated();
    }

    public function loadVendorsInLatestMonth()
    {
        return $this->vendorModel->getVendorsInLatestMonth();
    }
}
