<?php
namespace App\Services;

use App\Models\FinancesModel;
use App\Models\ProductsModel;
use App\Models\SalesModel;
use App\Models\TasksModel;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Support\Facades\DB;

class CalculateCashService
{
    private $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function loadCountCashTurnover()
    {
        $products = ProductsModel::all();
        $sales = SalesModel::all();
        $finances = FinancesModel::all();
    
        // Initialize sum variables
        $productSum = 0;
        $salesSum = 0;
        $financesSum = 0;
    
        // Calculate product sum
        foreach ($products as $product) {
            $productSum += $product->price * $product->count;
        }
    
        // Calculate sales and finances sums
        foreach ($sales as $sale) {
            $salesSum += $sale->price * $sale->quantity;
        }
        foreach ($finances as $finance) {
            $financesSum += $finance->net;
        }
    
        // Calculate the total sum
        $officialSum = $productSum + $salesSum + $financesSum;
    
        $currencyCode = $this->settingsService->loadSettingValue('currency');
    
        // Check if the currency code is empty or null, and if so, use a default currency code (e.g., 'USD')
        if (empty($currencyCode)) {
            $currencyCode = 'USD'; // Replace with your desired default currency code
        }
    
        // Ensure that the currency code is not an empty string
        if (empty($currencyCode)) {
            throw new \Exception('Invalid currency code');
        }
    
        return Money::$currencyCode($officialSum);
    }
    

    public function loadCountTodayIncome()
    {
        $products = ProductsModel::whereDate('created_at', Carbon::today())->get();
        $sales = SalesModel::whereDate('created_at', Carbon::today())->get();
        $finances = FinancesModel::whereDate('created_at', Carbon::today())->get();
        $productSum = 0;
        $salesSum = 0;
        $financesSum = 0;
    
        foreach ($products as $product) {
            $productSum += $product->price * $product->count;
        }
    
        foreach ($sales as $sale) {
            $salesSum += $sale->price * $sale->quantity;
        }
    
        foreach ($finances as $finance) {
            $financesSum += $finance->net;
        }
    
        $todayIncome = $productSum + $salesSum + $financesSum;
    
        $currencyCode = $this->settingsService->loadSettingValue('currency');
    
        // Check if the currency code is empty or null, and if so, use a default currency code (e.g., 'USD')
        if (empty($currencyCode)) {
            $currencyCode = 'USD'; // You can replace 'USD' with your desired default currency code
        }
    
        // Ensure that the currency code is not an empty string
        if (empty($currencyCode)) {
            throw new \Exception('Invalid currency code');
        }
    
        return Money::$currencyCode($todayIncome);
    }
    
    

    public function loadCountYesterdayIncome()
{
    $yesterday = Carbon::yesterday();

    // Fetch data for the specified date
    $products = ProductsModel::whereDate('created_at', $yesterday)->get();
    $sales = SalesModel::whereDate('created_at', $yesterday)->get();
    $finances = FinancesModel::whereDate('created_at', $yesterday)->get();

    // Initialize sum variables
    $salesSum = 0;
    $productSum = 0;
    $financesSum = 0;

    // Calculate sales and finances sums
    foreach ($sales as $sale) {
        $salesSum += $sale->price * $sale->quantity;
    }
    foreach ($finances as $finance) {
        $financesSum += $finance->net;
    }

    // Calculate product sum separately as it's not dependent on sales and finances
    foreach ($products as $product) {
        $productSum += $product->price * $product->count;
    }

    // Calculate the total income
    $yesterdayIncome = $productSum + $salesSum + $financesSum;

    $currencyCode = $this->settingsService->loadSettingValue('currency');

    // Check if the currency code is empty or null, and if so, use a default currency code (e.g., 'USD')
    if (empty($currencyCode)) {
        $currencyCode = 'USD'; // Replace with your desired default currency code
    }

    // Ensure that the currency code is not an empty string
    if (empty($currencyCode)) {
        throw new \Exception('Invalid currency code');
    }

    return Money::$currencyCode($yesterdayIncome);
}


    public function loadCountAllRowsInDb()
    {
        $counter = 0;
        $tables = DB::select('SHOW TABLES');

        $databaseName = DB::connection()->getDatabaseName();

        foreach ($tables as $table) {
            $counter += DB::table($table->{'Tables_in_' . $databaseName})->count();
        }

        return $counter;
    }

    public function loadTaskEveryMonth($isCompleted)
    {
        $dates = collect();
        foreach (range(-6, 0) as $i) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $dates->put($date, 0);
        }

        if ($isCompleted) {
            $posts = TasksModel::where('created_at', '>=', $dates->keys()->first())
                ->where('completed', '=', 1)
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as "count"')
                ])
                ->pluck('count', 'date');
        } else {
            $posts = TasksModel::where('created_at', '>=', $dates->keys()->first())
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as "count"')
                ])
                ->pluck('count', 'date');
        }

        $dates = $dates->merge($posts)->toArray();

        return array_values($dates);
    }
}
