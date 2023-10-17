<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Services\CompaniesService;
use App\Services\TransactionsService;
use App\Services\SystemLogService;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AccountsModel;
use App\Models\TransactionsModel;
use App\Models\VendorModel;
Use Illuminate\Support\Facades\Redirect;

class TransactionsController extends Controller
{
    private  $transactionsService;
    private  $systemLogsService;
    private  $companiesService;

    public function __construct(TransactionsService $transactionsService, SystemLogService $systemLogService, CompaniesService $companiesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->transactionsService = $transactionsService;
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

        return View::make('crm.transactions.create')->with([
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
        $dataOfCategories = DB::table('categories')->orderBy('sorder', 'asc')->take($categoryLimit)->pluck('name', 'id');
        $dataOfPayee = DB::table('vendors')->pluck('name', 'id');
        $dataOfPmethod = DB::table('sys_pmethods')->orderBy('sorder', 'asc')->pluck('name', 'id');

        return View::make('crm.transactions.expence-create')->with([
            'dataOfAccounts' => $dataOfAccounts,
            'dataOfCategories' => $dataOfCategories,
            'dataOfPayee' => $dataOfPayee,
            'dataOfPmethod' =>$dataOfPmethod
        ]);
    } 

    public function processShowTransactionsDetails($transactionId)
    {
        return View::make('crm.transactions.show')->with(['transaction' => $this->transactionsService->loadTransaction($transactionId)]);
    }

    public function processListOfTransactions()
    {
        $sumexpense = TransactionsModel::where('type', 'Expense')->sum('dr');
        $sumIncome = TransactionsModel::where('type', 'Income')->sum('cr');
        $latestBalance = DB::table('transactions')->latest('created_at')->value('bal');

        return View::make('crm.transactions.index')->with(
            [
                
                'transactionsPaginate' => $this->transactionsService->loadPagination(),
                'sumIncome' =>$sumIncome,
                'sumexpense'=>$sumexpense,
                'latestBalance'=>$latestBalance
            ]

            
        );
    }

    public function processRenderUpdateForm($transactionId)
    {
        $categoryLimit = 6;
        $dataOfAccounts = AccountsModel::pluck('account', 'id');
        $dataOfCategories = DB::table('categories')->orderBy('sorder', 'asc')->take($categoryLimit)->pluck('name', 'id');
        $dataOfPayers = DB::table('clients')->pluck('full_name', 'id');
        $dataOfPmethod = DB::table('sys_pmethods')->orderBy('sorder', 'asc')->pluck('name', 'id');

        return View::make('crm.transactions.edit')->with(
            [
                'transaction' => $this->transactionsService->loadTransaction($transactionId),
                'dataWithPluckOfCompanies' => $this->companiesService->loadCompanies(true),
                'dataOfAccounts' => $dataOfAccounts,
                'dataOfCategories' => $dataOfCategories,
                'dataOfPayers' => $dataOfPayers,
                'dataOfPmethod' =>$dataOfPmethod
            ]
        );
    }

    public function processStoreIncome(TransactionStoreRequest $request)
    {
        $transactionData = $request->validated();
        $adminId = $this->getAdminId();
    
        // Fetch the previous "bal" value from the database based on the user or account ID
        $previousBalValue = TransactionsModel::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->value('bal');
    
        // If there is no previous "bal" value, set it to 0
        if ($previousBalValue === null) {
            $previousBalValue = 0;
        }
    
        // Set the "cr" value to the new amount
        $transactionData['cr'] = $transactionData['amount'];
    
        // Calculate the updated balance (bal) by adding the new "cr" value to the previous "bal" value
        $updatedBalance = $previousBalValue + $transactionData['amount'];
    
        // Save the updated balance in the "bal" field
        $transactionData['bal'] = $updatedBalance;
    
        // Store the transaction in the database
        $storedTransactionId = $this->transactionsService->execute($transactionData, $adminId);
    
        if ($storedTransactionId) {
            $message = 'Transaction has been added with ID ' . $storedTransactionId . ' - ' . json_encode($validator);
            $this->systemLogsService->loadInsertSystemLogs($message, $this->systemLogsService::successCode, $this->getAdminId());            
            return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsStore'));
        }
    }

    public function incomeReport()
    {
        $daywiseIncome = TransactionsModel::where('type', 'Income')
        //->selectRaw('created_at, SUM(amount) as total_income')
        ->selectRaw('created_at, SUM(cr) as total_income, SUM(bal) as total_balance')

            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalIncome = $daywiseIncome->sum('total_income');

        return view('crm.transactions.income-report', compact('daywiseIncome', 'totalIncome'));
    }
    public function expenceReport()
    {
        $daywiseExpense = TransactionsModel::where('type', 'Expense')
        ->selectRaw('created_at, SUM(dr) as total_expense, SUM(bal) as total_balance')
            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->get();
        $totalExpense = $daywiseExpense->sum('total_expense');

        return view('crm.transactions.expence-report', compact('daywiseExpense', 'totalExpense'));
    }


    public function incomeVsExpense()
    {
        $data = TransactionsModel::whereIn('type', ['Income', 'Expense'])
        ->orderBy('created_at', 'desc') //  I have a 'created_at' column to track record creation
        ->limit(10)
        ->get();

        $sumexpense = TransactionsModel::where('type', 'Expense')->sum('dr');
        $sumIncome = TransactionsModel::where('type', 'Income')->sum('cr');
        $latestBalance = DB::table('transactions')->latest('created_at')->value('bal');
        return view('crm.transactions.incomeVsExpense', compact('data','sumexpense','sumIncome','latestBalance'));
    }
    

    public function incomeReportBetweenDates(TransactionStoreRequest $request)
    {
        $startDate = Carbon::parse($request->input('created_at'))->startOfDay();
        $endDate = Carbon::parse($request->input('created_at'))->endOfDay();

        $daywiseIncome = TransactionsModel::where('type', 'Income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_income')
            ->groupBy('created_at')
            ->orderBy('created_at', 'asc')
            ->pluck('total_income', 'date');

        return view('crm.transactions.income-report-between-dates', compact('daywiseIncome', 'startDate', 'endDate'));
    }



    // public function processStoreExpense(TransactionStoreRequest $request)
    // {
    //     $transactionData = $request->validated();
    //     $adminId = $this->getAdminId();

    //     // Fetch the previous "bal" value from the database based on the user or account ID
    //     $previousBalValue = TransactionsModel::where('admin_id', $adminId)
    //         ->orderBy('created_at', 'desc')
    //         ->value('bal');

    //     // If there is no previous "bal" value, set it to 0
    //     if ($previousBalValue === null) {
    //         $previousBalValue = 0;
    //     }

    //     // Set the "dr" value to the new amount (expense amount)
    //     $transactionData['dr'] = $transactionData['amount'];

    //     // Calculate the updated balance (bal) by subtracting the new "dr" value from the previous "bal" value
    //     $updatedBalance = $previousBalValue - $transactionData['amount'];

    //     // Save the updated balance in the "bal" field
    //     $transactionData['bal'] = $updatedBalance;

    //     // Store the transaction in the database
    //     $storedTransactionId = $this->transactionsService->execute($transactionData, $adminId);

    //     if ($storedTransactionId) {
    //         $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been added with id: ' . $storedTransactionId, $this->systemLogsService::successCode, $adminId);
    //         return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsStore'));
    //     } else {
    //         return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsStore'));
    //     }
    // }


    public function processStoreExpense(TransactionStoreRequest $request)
    {
        $transactionData = $request->validated();
        $adminId = $this->getAdminId();

        // Fetch the previous "bal" value from the database based on the user or account ID
        $previousBalValue = TransactionsModel::where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->value('bal');

        // If there is no previous "bal" value, set it to 0
        if ($previousBalValue === null) {
            $previousBalValue = 0;
        }

        // Set the "type" to 'Expense'
        $transactionData['type'] = 'Expense';

        // Set the "dr" value to the new amount (expense amount)
        $transactionData['dr'] = $transactionData['amount'];

        // Calculate the updated balance (bal) by subtracting the new "dr" value from the previous "bal" value
        $updatedBalance = $previousBalValue - $transactionData['amount'];

        // Save the updated balance in the "bal" field
        $transactionData['bal'] = $updatedBalance;

        // Store the transaction in the database
        $storedTransactionId = $this->transactionsService->execute($transactionData, $adminId);

        if ($storedTransactionId) {
            $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been added with id: ' . $storedTransactionId, $this->systemLogsService::successCode, $adminId);
            return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsStore'));
        }
    }



    // public function processStoreTransaction(TransactionStoreRequest $request)
    // {
    //     $transactionData = $request->validated();
    //     $adminId = $this->getAdminId();
    
    //     // Fetch the previous "bal" value from the database based on the user or account ID
    //     $previousBalValue = TransactionsModel::where('admin_id', $adminId)
    //         ->orderBy('created_at', 'desc')
    //         ->value('bal');
    
    //     // If there is no previous "bal" value, set it to 0
    //     if ($previousBalValue === null) {
    //         $previousBalValue = 0;
    //     }
    
    //     switch (strtolower($transactionData['type'])) {
    //         case 'income':
    //             // Set the "cr" value to the new amount
    //             $transactionData['cr'] = $transactionData['amount'];
    //             // Calculate the updated balance (bal) by adding the new "cr" value to the previous "bal" value
    //             $updatedBalance = $previousBalValue + $transactionData['amount'];
    //             break;
    
    //         case 'expense':
    //             // Set the "dr" value to the new amount (expense amount)
    //             $transactionData['dr'] = $transactionData['amount'];
    //             // Calculate the updated balance (bal) by subtracting the new "dr" value from the previous "bal" value
    //             $updatedBalance = $previousBalValue - $transactionData['amount'];
    //             break;
    
    //         default:
    //             // Invalid transaction type, handle error or provide a default action
    //             return Redirect::back()->with('message_danger', 'Invalid transaction type.');
    //     }
    
    //     // Save the updated balance in the "bal" field
    //     $transactionData['bal'] = $updatedBalance;
    
    //     // Store the transaction in the database
    //     $storedTransactionId = $this->transactionsService->execute($transactionData, $adminId);
    
    //     if ($storedTransactionId) {
    //         $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been added with id: ' . $storedTransactionId, $this->systemLogsService::successCode, $adminId);
    //         return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsStore'));
    //     } else {
    //         return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsStore'));
    //     }
    // }
    


    public function processUpdateTransaction(TransactionUpdateRequest $request, $transactionId)
    {
        if ($this->transactionsService->update($transactionId, $request->validated())) {
            return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsUpdate'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorTransactionsUpdate'));
        }
    }

    public function processDeleteTransaction($transactionId)
    {
        $dataOfTransactions = $this->transactionsService->loadTransaction($transactionId);

        $dataOfTransactions->delete();

        $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been deleted with id: ' . $dataOfTransactions->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsDelete'));
    }

    public function processTransactionsetIsActive($transactionId, $value)
    {
        if ($this->transactionsService->loadIsActive($transactionId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been enabled with id: ' . $transactionId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessTransactionsActive' : 'TransactionsIsNowDeactivated';

            return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsActive'));
        }
    }
}
