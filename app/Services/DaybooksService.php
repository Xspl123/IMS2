<?php

namespace App\Services;
use DB;
use App\Models\DaybooksModel;
use Illuminate\Support\Facades\Config;

class DaybooksService
{
    private  $daybooksModel;

    public function __construct()
    {
        $this->daybooksModel = new DaybooksModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->daybooksModel->storeDaybook($requestedData, $adminId);
    }

    public function update(int $daybookId, array $requestedData)
    {
        return $this->daybooksModel->updateDaybook($daybookId, $requestedData);
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

    public function loadDaybooks()
    {
        return $this->daybooksModel->getDaybooksSortedByCreatedAt()
                                    ->take(3);
                                    
    }


    public function getLatestDrValue()
    {
        $latestDrValue = DaybooksModel::orderBy('created_at', 'desc')
                                    
                                    ->value('dr');

        return $latestDrValue;
    }


    public function loadPagination()
    {
        return $this->daybooksModel->getPaginate();
    }

    public function loadDaybook(int $daybookId)
    {
        return $this->daybooksModel::find($daybookId);
    }

    public function loadIsActive(int $daybookId, int $value)
    {
        return $this->daybooksModel->setActive($daybookId, $value);
    }

    public function loadCountDaybooks()
    {
        return $this->daybooksModel->countDaybooks();
    }
}
