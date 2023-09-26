<?php

namespace App\Services;

class GraphDataService
{
    private  $width = 400;
    private $height = 200;

    public function taskGraphData() {

        $cash = new CalculateCashService();

        $taskGraphData = app()->chartjs
            ->name('taskGraphData')
            ->type('bar')
            ->size(['width' => $this->width, 'height' => $this->height])
            ->labels(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
            ->datasets([
                [
                    "label" => "Added tasks",
                    'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                    'borderColor' => "rgba(38, 185, 154, 0.7)",
                    "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                    "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => $cash->loadTaskEveryMonth($isCompleted = false)
                ],
                [
                    "label" => "Completed tasks",
                    'backgroundColor' => "rgba(38, 80, 186, 0.55)",
                    'borderColor' => "rgba(38, 80, 186, 1)",
                    "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                    "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => $cash->loadTaskEveryMonth($isCompleted = true)
                ]
            ])
            ->options([]);

        return $taskGraphData;
    }

    
    public function itemsCountGraphData()
    {
        $itemsCountGraphData = app()->chartjs
            ->name('cashTurnoverGraphData')
            ->type('doughnut')
            ->size(['width' => $this->width, 'height' => $this->height])
            ->labels(['Products', 'Sales', 'Purchase',  'Rents'])
            ->datasets([
                [
                    'backgroundColor' => [
                        'rgba(0, 255, 0, 0.6)',     // Green for Products
                        'rgba(0, 0, 255, 0.6)',     // Blue for Sales
                        'rgba(255, 255, 0, 0.6)',   // Yellow for Purchase
                        // 'rgba(255, 165, 0, 0.6)',   // Orange for Deals
                        // 'rgba(255, 192, 203, 0.6)', // Pink for Finance
                        'rgba(255, 0, 0, 0.6)'      // Red for Rents (Unique color)
                    ],
                    'data' => [
                        $this->getCalculateProducts(),
                        $this->getCalculateSales(),
                        $this->getCalculatePurchase(),
                        // $this->getCalculateDeals(),
                        // $this->getCalculateFinances(),
                        $this->getCalculateRents()
                    ]
                ]
            ])
            ->options([]);
    
        return $itemsCountGraphData;
    }

    private function getCalculateDeals()
    {
        $dealsService = new DealsService();

        return $dealsService->loadCountDeals();
    }

    private function getCalculateFinances()
    {
        $financesService = new FinancesService();

        return $financesService->loadCountFinances();
    }

    private function getCalculateProducts()
    {
        $productsService = new ProductsService();

        return $productsService->loadCountProducts();
    }

    private function getCalculateSales()
    {
        $salesService = new SalesService();

        return $salesService->loadCountSales();
    }
    private function getCalculateRents()
    {
        $rentsService = new RentsService();

        return $rentsService->loadCountRents();
    }

    private function getCalculatePurchase()
    {
        $purchaseService = new PurchaseService();

        return $purchaseService->loadCountPurchases();
    }
}
