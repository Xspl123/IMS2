<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\DayBooksStoreRequest;
use App\Http\Requests\DaybookUpdateRequest;
use App\Services\CompaniesService;
use App\Services\DaybooksService;
use App\Services\SystemLogService;
use View;
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
        $sumexpense = DaybooksModel::where('type', 'Expense')->sum('dr');
        $sumIncome = DaybooksModel::where('type', 'Income')->sum('cr');
        $latestBalance = DB::table('daybooks')->latest('created_at')->value('bal');

        return View::make('crm.daybooks.index')->with(
            [
                
                'daybooksPaginate' => $this->daybooksService->loadPagination(),
                'sumIncome' =>$sumIncome,
                'sumexpense'=>$sumexpense,
                'latestBalance'=>$latestBalance
            ]
        );
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
    
        // Use Laravel validation to check if the amount is greater than the wallet amount
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01', // Adjust the min value as needed
        ]);
    
        if ($validator->fails() || $amount > $wallet_amt) {
            // Validation fails or amount is greater than wallet amount
            $errorMessage = $amount > $wallet_amt ? 'Wallet balance is insufficient' : 'Invalid Amount';
    
            return redirect()->back()
                ->withErrors($validator)
                ->with('message_danger', $errorMessage);
        } 
        else{
            $walletupdate = $wallet_amt - $amount;

        // Validate the incoming request data using the DayBooksStoreRequest rules
        $daybookData = $request->validated();
        // Get the admin ID
        $adminId = $this->getAdminId();
        DB::table('wallet')->where('id',$adminId)->update(['amount'=>$walletupdate]);
        // Fetch the previous "bal" value from the database based on the user or account ID
        $previousBalValue = DaybooksModel::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->value('bal');
        // If there is no previous "bal" value, set it to 0
        if ($previousBalValue === null) {
            $previousBalValue = 0;
        }
        
        // Set the "type" to 'Expense'
        $daybookData['type'] = 'Expense';
        
        $daybookData['dr'] = $daybookData['amount'];
        // Calculate the updated balance (bal) by subtracting the new "dr" value from the previous "bal" value
        $updatedBalance = $previousBalValue - $daybookData['amount'];
        
        // Save the updated balance in the "bal" field
        $daybookData['bal'] = $updatedBalance;
        
        // Store the transaction in the database using the daybooksService
        $storedDaybookId = $this->daybooksService->execute($daybookData, $adminId);
        
        // Check if the transaction was successfully stored
        if ($storedDaybookId) {
            // Log the success and redirect with a success message
            $this->systemLogsService->loadInsertSystemLogs('DaybooksModel has been added with id: ' . $storedDaybookId, $this->systemLogsService::successCode, $adminId);
            return redirect()->route('daybooks')->with('message_success', $this->getMessage('messages.SuccessDaybooksStore'));
        } else {
            // If storing fails, redirect back with an error message
            return redirect()->back()->with('message_danger', $this->getMessage('messages.ErrorDaybooksStore'));
        }
       }
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
