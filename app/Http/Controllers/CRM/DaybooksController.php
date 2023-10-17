<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\DayBooksStoreRequest;
use App\Http\Requests\DaybookUpdateRequest;
use App\Services\CompaniesService;
use App\Services\DaybooksService;
use App\Services\SystemLogService;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\AccountsModel;
use App\Models\DaybooksModel;
use App\Models\VendorModel;
Use Illuminate\Support\Facades\Redirect;

class DaybooksController extends Controller
{
    private  $daybooksService;
    private  $systemLogsService;
    private  $companiesService;

    public function __construct(DaybooksService $daybooksService, SystemLogService $systemLogService, CompaniesService $companiesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->daybooksService = $daybooksService;
        $this->systemLogsService = $systemLogService;
        $this->companiesService = $companiesService;
    }

    public function processRenderCreateForm()
    {
        $categoryLimit = 6;
        $dataOfAccounts = AccountsModel::pluck('account', 'id');
        $dataOfCategories = DB::table('categories')->orderBy('sorder', 'asc')->take($categoryLimit)->pluck('name', 'id');
        $dataOfPayers = DB::table('clients')->pluck('full_name', 'id');
        $dataOfPmethod = DB::table('sys_pmethods')->orderBy('sorder', 'asc')->pluck('name', 'id');

        return View::make('crm.daybooks.create')->with([
            'dataOfAccounts' => $dataOfAccounts,
            'dataOfCategories' => $dataOfCategories,
            'dataOfPayers' => $dataOfPayers,
            'dataOfPmethod' =>$dataOfPmethod
        ]);
    }

    public function processRenderCreateExpenceForm()
    {
        $categoryLimit = 6;
        $dataOfAccounts = AccountsModel::pluck('account', 'id');
        $dataOfCategories = DB::table('expense_categories')->pluck('name', 'id');        
        $dataOfPayee = DB::table('vendors')->pluck('name', 'id');
        $dataOfPmethod = DB::table('sys_pmethods')->orderBy('sorder', 'asc')->pluck('name', 'id');
        $wallet = DB::table('wallet')->first();


        return View::make('crm.daybooks.expence-create')->with([
            'dataOfAccounts' => $dataOfAccounts,
            'dataOfCategories' => $dataOfCategories,
            'dataOfPayee' => $dataOfPayee,
            'dataOfPmethod' =>$dataOfPmethod,
            'wallet' => $wallet
        ]);
    } 

    public function processShowDaybooksDetails($daybookId)
    {
        return View::make('crm.daybooks.show')->with(['daybook' => $this->daybooksService->loadDaybook($daybookId)]);
    }


    public function processListOfDaybooks()
    {
        // Calculate total expenses for different time periods
        $adminId = $this->getAdminId();
        $totalExpenseDayWise = $this->calculateExpensesDayWise();
        $totalExpenseWeekWise = $this->calculateExpensesWeekWise();
        $totalExpenseMonthWise = $this->calculateExpensesMonthWise();

        // Calculate average expenses for different time periods
        $averageExpenseDayWise = calculateAverage($totalExpenseDayWise, $this->countExpenses('day'));
        $averageExpenseWeekWise = calculateAverage($totalExpenseWeekWise, $this->countExpenses('week'));
        $averageExpenseMonthWise = calculateAverage($totalExpenseMonthWise, $this->countExpenses('month'));

        // Calculate expenses for the last three months using the helper method
        $today = Carbon::now();
        $threeMonthsAgo = clone $today;
        $threeMonthsAgo->subMonths(3);
        $totalExpenseLastThreeMonths = calculateExpensesBetweenDates($threeMonthsAgo, $today);

        // Calculate expenses for the last week
        $startDateLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endDateLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $totalExpenseLastWeek = DaybooksModel::where('type', 'Expense')
            ->whereBetween('created_at', [$startDateLastWeek, $endDateLastWeek])
            ->sum('dr');

        // Calculate expenses for the last month
        $startDateLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endDateLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $totalExpenseLastMonth = DaybooksModel::where('type', 'Expense')
            ->whereBetween('created_at', [$startDateLastMonth, $endDateLastMonth])
            ->sum('dr');

        // Calculate average expenses for the last week and last month
        $daysInLastWeek = $startDateLastWeek->diffInDays($endDateLastWeek) + 1;
        $averageExpenseLastWeek = $totalExpenseLastWeek / $daysInLastWeek;

        $daysInLastMonth = $startDateLastMonth->diffInDays($endDateLastMonth) + 1;
        $averageExpenseLastMonth = $totalExpenseLastMonth / $daysInLastMonth;

        // Initialize default values for max and min expenses
        $maxExpenseLastThreeMonths = null;
        $minExpenseLastThreeMonths = null;

        // Check if $totalExpenseLastThreeMonths is an array and not empty
        if (is_array($totalExpenseLastThreeMonths) && count($totalExpenseLastThreeMonths) > 0) {
            // Find the maximum and minimum expenses for the last three months
            $maxExpenseLastThreeMonths = max($totalExpenseLastThreeMonths);
            $minExpenseLastThreeMonths = min($totalExpenseLastThreeMonths);
        }

        // Retrieve the required data
        $daybooksPaginate = $this->daybooksService->loadPagination();
        $sumExpense = DaybooksModel::where('type', 'Expense')->sum('dr');
        $latestBalance = DB::table('daybooks')->latest('created_at')->value('dr');

        return view('crm.daybooks.index', compact(
            'daybooksPaginate',
            'sumExpense',
            'latestBalance',
            'totalExpenseDayWise',
            'totalExpenseWeekWise',
            'totalExpenseMonthWise',
            'averageExpenseDayWise',
            'averageExpenseWeekWise',
            'averageExpenseMonthWise',
            'totalExpenseLastThreeMonths',
            'maxExpenseLastThreeMonths',
            'minExpenseLastThreeMonths',
            'totalExpenseLastWeek',
            'totalExpenseLastMonth',
            'averageExpenseLastWeek',
            'averageExpenseLastMonth' 
        ));
    }
    
    

    private function countExpenses($period)
    {
        switch ($period) {
            case 'day':
                return DaybooksModel::where('type', 'Expense')
                    ->whereDate('created_at', Carbon::today())
                    ->count();
            case 'week':
                return DaybooksModel::where('type', 'Expense')
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->count();
            case 'month':
                return DaybooksModel::where('type', 'Expense')
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count();
            default:
                return 0;
        }
    }

    
    public function processRenderUpdateForm($daybookId)
    {
        $categoryLimit = 6;
        $dataOfAccounts = AccountsModel::pluck('account', 'id');
        $dataOfCategories = DB::table('expense_categories')->pluck('name', 'id');
        $dataOfPayers = DB::table('clients')->pluck('full_name', 'id');
        $dataOfPmethod = DB::table('sys_pmethods')->orderBy('sorder', 'asc')->pluck('name', 'id');

        return View::make('crm.daybooks.edit')->with(
            [
                'daybook' => $this->daybooksService->loadDaybook($daybookId),
                'dataWithPluckOfCompanies' => $this->companiesService->loadCompanies(true),
                'dataOfAccounts' => $dataOfAccounts,
                'dataOfCategories' => $dataOfCategories,
                'dataOfPayers' => $dataOfPayers,
                'dataOfPmethod' =>$dataOfPmethod
            ]
        );
    }

    public function processStoreIncome(DayBooksStoreRequest $request)
    {
        $daybookData = $request->validated();
        $adminId = $this->getAdminId();
    
        // Fetch the previous "bal" value from the database based on the user or account ID
        $previousBalValue = DaybooksModel::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->value('bal');
    
        // If there is no previous "bal" value, set it to 0
        if ($previousBalValue === null) {
            $previousBalValue = 0;
        }
    
        // Set the "cr" value to the new amount
        $daybookData['cr'] = $daybookData['amount'];
    
        // Calculate the updated balance (bal) by adding the new "cr" value to the previous "bal" value
        $updatedBalance = $previousBalValue + $daybookData['amount'];
    
        // Save the updated balance in the "bal" field
        $daybookData['bal'] = $updatedBalance;
    
        // Store the daybook in the database
        $storedDaybookId = $this->DaybooksService->execute($daybookData, $adminId);
    
        if ($storedDaybookId) {
            $this->systemLogsService->loadInsertSystemLogs('DaybooksModel has been added with id: ' . $storedDaybookId, $this->systemLogsService::successCode, $adminId);
            return Redirect::to('daybooks')->with('message_success', $this->getMessage('messages.SuccessDaybooksStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorDaybooksStore'));
        }
    }

    public function incomeReport()
    {
        $daywiseIncome = DaybooksModel::where('type', 'Income')
        //->selectRaw('created_at, SUM(amount) as total_income')
        ->selectRaw('created_at, SUM(cr) as total_income, SUM(bal) as total_balance')

            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalIncome = $daywiseIncome->sum('total_income');

        return view('crm.daybooks.income-report', compact('daywiseIncome', 'totalIncome'));
    }
    public function expenceReport()
    {
        $daywiseExpense = DaybooksModel::where('type', 'Expense')
        ->selectRaw('created_at, SUM(dr) as total_expense, SUM(bal) as total_balance')
            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->get();
        $totalExpense = $daywiseExpense->sum('total_expense');

        return view('crm.daybooks.expence-report', compact('daywiseExpense', 'totalExpense'));
    }


    public function incomeVsExpense()
    {
        $data = DaybooksModel::whereIn('type', ['Income', 'Expense'])
        ->orderBy('created_at', 'desc') //  I have a 'created_at' column to track record creation
        ->limit(10)
        ->get();

        $sumexpense = DaybooksModel::where('type', 'Expense')->sum('dr');
        $sumIncome = DaybooksModel::where('type', 'Income')->sum('cr');
        $latestBalance = DB::table('daybooks')->latest('created_at')->value('bal');
        return view('crm.daybooks.incomeVsExpense', compact('data','sumexpense','sumIncome','latestBalance'));
    }
    

    public function incomeReportBetweenDates(DaybookStoreRequest $request)
    {
        $startDate = Carbon::parse($request->input('created_at'))->startOfDay();
        $endDate = Carbon::parse($request->input('created_at'))->endOfDay();

        $daywiseIncome = DaybooksModel::where('type', 'Income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_income')
            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->pluck('total_income', 'date');

        return view('crm.daybooks.income-report-between-dates', compact('daywiseIncome', 'startDate', 'endDate'));
    }


    public function processStoreExpense(DayBooksStoreRequest $request)
    {
        $wallet_amt = $request->wallet_amt;
        $amount = $request->amount;
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails() || $amount > $wallet_amt) {
            $errorMessage = $amount > $wallet_amt ? 'Wallet balance is insufficient' : 'Invalid Amount';

            return redirect()->back()
                ->withErrors($validator)
                ->with('message_danger', $errorMessage);
        } else {
            $walletupdate = $wallet_amt - $amount;

            $daybookData = $request->validated();

            $adminId = $this->getAdminId();
            DB::table('wallet')->where('admin_id', $adminId)->update(['amount' => $walletupdate]);

            $previousBalValue = DaybooksModel::where('admin_id', $adminId)
                ->orderBy('created_at', 'desc')
                ->value('bal');
            if ($previousBalValue === null) {
                $previousBalValue = 0;
            }

            $daybookData['type'] = 'Expense';
            $daybookData['dr'] = $daybookData['amount'];

            $storedDaybookId = $this->daybooksService->execute($daybookData, $adminId);

            if ($storedDaybookId) {
                $message = 'Daybook has been added with ID ' . $storedDaybookId . ' - ' . json_encode($validator);
                $this->systemLogsService->loadInsertSystemLogs($message, $this->systemLogsService::successCode, $this->getAdminId());
                // Calculate expenses for different time periods
                $totalExpenseDayWise = $this->calculateExpensesDayWise();
                $totalExpenseWeekWise = $this->calculateExpensesWeekWise();
                $totalExpenseMonthWise = $this->calculateExpensesMonthWise();

                return redirect()
                    ->route('daybooks')
                    ->with('message_success', $this->getMessage('messages.SuccessDaybooksStore'))
                    ->with('totalExpenseDayWise', $totalExpenseDayWise)
                    ->with('totalExpenseWeekWise', $totalExpenseWeekWise)
                    ->with('totalExpenseMonthWise', $totalExpenseMonthWise);
            } else {
                return redirect()->back()->with('message_danger', $this->getMessage('messages.ErrorDaybooksStore'));
            }
        }
    }

    private function calculateExpensesDayWise()
    {
        return DaybooksModel::where('type', 'Expense')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
    }
    
    private function calculateExpensesWeekWise()
    {
        return DaybooksModel::where('type', 'Expense')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');
    }
    
    private function calculateExpensesMonthWise()
    {
        return DaybooksModel::where('type', 'Expense')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
    }
    
    
    public function processUpdateDaybook(DaybookUpdateRequest $request, $daybookId)
    {
        if ($this->daybooksService->update($daybookId, $request->validated())) {
            return Redirect::to('daybooks')->with('message_success', $this->getMessage('messages.SuccessDaybooksUpdate'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorDaybooksUpdate'));
        }
    }

    public function processDeleteDaybook($daybookId)
    {
        $dataOfDaybooks = $this->daybooksService->loadDaybook($daybookId);

        $dataOfDaybooks->delete();

        $this->systemLogsService->loadInsertSystemLogs('DaybooksModel has been deleted with id: ' . $dataOfDaybooks->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('daybooks')->with('message_success', $this->getMessage('messages.SuccessDaybooksDelete'));
    }

    public function processDaybooksetIsActive($daybookId, $value)
    {
        if ($this->daybooksService->loadIsActive($daybookId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('DaybooksModel has been enabled with id: ' . $daybookId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessDaybooksActive' : 'DaybooksIsNowDeactivated';

            return Redirect::to('daybooks')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorDaybooksActive'));
        }
    }
}