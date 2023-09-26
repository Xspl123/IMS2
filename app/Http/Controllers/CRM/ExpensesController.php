<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Services\CompaniesService;
use App\Services\TransactionsService;
use App\Services\SystemLogService;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\AccountsModel;
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

    // public function processRenderCreateForm()
    // {
    //     return View::make('crm.transactions.create')->with(['dataWithPluckOfCompanies' => $this->companiesService->loadCompanies(true)]);
    // }

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

    public function processShowTransactionsDetails($transactionId)
    {
        return View::make('crm.transactions.show')->with(['transaction' => $this->transactionsService->loadTransaction($transactionId)]);
    }

    public function processListOfTransactions()
    {
        

        return View::make('crm.transactions.index')->with(
            [
                
                'transactionsPaginate' => $this->transactionsService->loadPagination()
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

    public function processStoreTransaction(TransactionStoreRequest $request)
    {
        $storedTransactionId = $this->transactionsService->execute($request->validated(), $this->getAdminId());

        if ($storedTransactionId) {
            $this->systemLogsService->loadInsertSystemLogs('TransactionsModel has been add with id: ' . $storedTransactionId, $this->systemLogsService::successCode, $this->getAdminId());
            return Redirect::to('transactions')->with('message_success', $this->getMessage('messages.SuccessTransactionsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorTransactionsStore'));
        }
    }

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
