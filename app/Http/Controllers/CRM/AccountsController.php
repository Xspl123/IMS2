<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Services\CompaniesService;
use App\Services\AccountsService;
use App\Services\SystemLogService;
use View;
Use Illuminate\Support\Facades\Redirect;

class AccountsController extends Controller
{
    private  $accountsService;
    private  $systemLogsService;
    private  $companiesService;

    public function __construct(AccountsService $accountsService, SystemLogService $systemLogService, CompaniesService $companiesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->accountsService = $accountsService;
        $this->systemLogsService = $systemLogService;
        $this->companiesService = $companiesService;
    }

    public function processRenderCreateForm()
    {
        return View::make('crm.accounts.create')->with(['dataWithPluckOfCompanies' => $this->companiesService->loadCompanies(true)]);
    }

    public function processShowAccountsDetails($accountId)
    {
        return View::make('crm.accounts.show')->with(['account' => $this->accountsService->loadAccount($accountId)]);
    }

    public function processListOfAccounts()
    {
        return View::make('crm.accounts.index')->with(
            [
                'accountsPaginate' => $this->accountsService->loadPagination()
            ]
        );
    }

    public function processRenderUpdateForm($accountId)
    {
        return View::make('crm.accounts.edit')->with(
            [
                'account' => $this->accountsService->loadAccount($accountId),
                'dataWithPluckOfCompanies' => $this->companiesService->loadCompanies(true)
            ]
        );
    }

    public function processStoreAccount(AccountStoreRequest $request)
    {
        $storedAccountId = $this->accountsService->execute($request->validated(), $this->getAdminId());

        if ($storedAccountId) {
            $this->systemLogsService->loadInsertSystemLogs('AccountsModel has been add with id: ' . $storedAccountId, $this->systemLogsService::successCode, $this->getAdminId());
            return Redirect::to('accounts')->with('message_success', $this->getMessage('messages.SuccessAccountsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorAccountsStore'));
        }
    }

    public function processUpdateAccount(AccountUpdateRequest $request, $accountId)
    {
        if ($this->accountsService->update($accountId, $request->validated())) {
            return Redirect::to('accounts')->with('message_success', $this->getMessage('messages.SuccessAccountsUpdate'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorAccountsUpdate'));
        }
    }

    public function processDeleteAccount($accountId)
    {
        $dataOfAccounts = $this->accountsService->loadAccount($accountId);

        $dataOfAccounts->delete();

        $this->systemLogsService->loadInsertSystemLogs('AccountsModel has been deleted with id: ' . $dataOfAccounts->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('accounts')->with('message_success', $this->getMessage('messages.SuccessAccountsDelete'));
    }

    public function processAccountSetIsActive($accountId, $value)
    {
        if ($this->accountsService->loadIsActive($accountId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('AccountsModel has been enabled with id: ' . $accountId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessAccountsActive' : 'AccountsIsNowDeactivated';

            return Redirect::to('accounts')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorAccountsActive'));
        }
    }
}
