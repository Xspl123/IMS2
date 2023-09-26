<?php

namespace App\Http\Controllers;

use App\Enums\SystemEnums;
use App\Services\CalculateCashService;
use App\Services\ClientService;
use App\Services\CompaniesService;
use App\Services\VendorsService;
use App\Services\DealsService;
use App\Services\EmployeesService;
use App\Services\FinancesService;
use App\Services\HelpersFncService;
use App\Services\ProductsService;
use App\Services\SalesService;
use App\Services\RentsService;
use App\Services\PurchaseService;
use App\Services\SettingsService;
use App\Services\SystemLogService;
use App\Services\TasksService;
use App\Services\TransactionsService;
use App\Models\CompaniesModel;
use App\Models\VendorModel;
use App\Models\TransactionModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;


class DashboardController extends Controller
{
    private $clientService;
    private $helpersFncService;
    private $vendorsService;
    private $companiesService;
    private $productsService;
    private $calculateCashService;
    private $employeesService;
    private $dealsService;
    private $financesService;
    private $tasksService;
    private $salesService;
    private $rentsService;
    private $purchaseService;
    private $systemLogService;
    private $settingsService;
    private $transactionsService;


    private $cacheTime = 5940000; // cache set for 99 minutes


    public function __construct()
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->clientService = new ClientService();
        $this->helpersFncService = new HelpersFncService();
        $this->vendorsService = new VendorsService(new VendorModel());
        $this->companiesService = new CompaniesService(new CompaniesModel());
        $this->productsService = new ProductsService();
        $this->calculateCashService = new CalculateCashService();
        $this->employeesService = new EmployeesService();
        $this->dealsService = new DealsService();
        $this->financesService = new FinancesService();
        $this->tasksService = new TasksService();
        $this->salesService = new SalesService();
        $this->rentsService = new RentsService();
        $this->purchaseService = new PurchaseService();
        $this->systemLogService = new SystemLogService();
        $this->settingsService = new SettingsService();
        $this->transactionsService = new transactionsService();
    }

    public function index()
    {
        $this->storeInCacheUsableVariables();

        

        return View::make('index')->with(
            [
                'tasksGraphData' => $this->taskGraphData(),
                'itemsCountGraphData' => $this->itemsCountGraphData(),
                'dataWithAllTasks' => $this->helpersFncService->formatTasks(),
                'dataWithAllVendors' => $this->vendorsService->loadVendorsByCreatedAt(),
                'dataWithAllCompanies' => $this->companiesService->loadCompaniesByCreatedAt(),
                'dataWithAllProducts' => $this->productsService->loadProductsByCreatedAt(),
                'dataWithAllTransactions' => $this->transactionsService->loadTransactions(),
                'dataWithAllSales' => $this->salesService->loadSales(),
                'currency' => $this->settingsService->loadSettingValue(SystemEnums::currency)
            ]
        );
    }

    private function storeInCacheUsableVariables()
    {
        Cache::put('countClients', $this->clientService->loadCountClients(), $this->cacheTime);
        Cache::put('deactivatedClients', $this->clientService->loadDeactivatedClients(), $this->cacheTime);
        Cache::put('clientsInLatestMonth', $this->clientService->loadClientsInLatestMonth(), $this->cacheTime);
        Cache::put('countVendors', $this->vendorsService->loadCountVendors(), $this->cacheTime);
        Cache::put('countCompanies', $this->companiesService->loadCountCompanies(), $this->cacheTime);
        Cache::put('countEmployees', $this->employeesService->loadCountEmployees(), $this->cacheTime);
        Cache::put('countDeals', $this->dealsService->loadCountDeals(), $this->cacheTime);
        Cache::put('countFinances', $this->financesService->loadCountFinances(), $this->cacheTime);
        Cache::put('countProducts', $this->productsService->loadCountProducts(), $this->cacheTime);
        Cache::put('countTasks', $this->tasksService->loadCountTasks(), $this->cacheTime);
        Cache::put('countSales', $this->salesService->loadCountSales(), $this->cacheTime);
        Cache::put('countRents', $this->rentsService->loadCountRents(), $this->cacheTime);
        Cache::put('countTransactions', $this->transactionsService->loadCountTransactions(), $this->cacheTime);
        Cache::put('countPurchases', $this->purchaseService->loadCountPurchases(), $this->cacheTime);
        Cache::put('deactivatedCompanies', $this->companiesService->loadDeactivatedCompanies(), $this->cacheTime);
        Cache::put('todayIncome', $this->calculateCashService->loadCountTodayIncome(), $this->cacheTime);
        Cache::put('yesterdayIncome', $this->calculateCashService->loadCountYesterdayIncome(), $this->cacheTime);
        Cache::put('cashTurnover', $this->calculateCashService->loadCountCashTurnover(), $this->cacheTime);
        Cache::put('countAllRowsInDb', $this->calculateCashService->loadCountAllRowsInDb(), $this->cacheTime);
        Cache::put('countSystemLogs', $this->systemLogService->loadCountLogs(), $this->cacheTime);
        Cache::put('vendorsInLatestMonth', $this->vendorsService->loadVendorsInLatestMonth(), $this->cacheTime);
        Cache::put('companiesInLatestMonth', $this->companiesService->loadCompaniesInLatestMonth(), $this->cacheTime);
        Cache::put('employeesInLatestMonth', $this->employeesService->loadEmployeesInLatestMonth(), $this->cacheTime);
        Cache::put('deactivatedEmployees', $this->employeesService->loadDeactivatedEmployees(), $this->cacheTime);
        Cache::put('deactivatedDeals', $this->dealsService->loadDeactivatedDeals(), $this->cacheTime);
        Cache::put('dealsInLatestMonth', $this->dealsService->loadDealsInLatestMonth(), $this->cacheTime);
        Cache::put('completedTasks', $this->tasksService->loadCompletedTasks(), $this->cacheTime);
        Cache::put('uncompletedTasks', $this->tasksService->loadUncompletedTasks(), $this->cacheTime);
    }

    public function processReloadInformation()
    {
        $this->storeInCacheUsableVariables();

        return Redirect::back()->with('message_success', $this->getMessage('messages.cacheReloaded'));
    }
}
