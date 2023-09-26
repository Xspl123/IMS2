<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Services\CompaniesService;
use App\Services\DealsService;
use App\Services\ClientService;
use App\Services\SystemLogService;
use App\Models\ClientsModel;
use View;
use Illuminate\Support\Facades\Redirect;

class CompaniesController extends Controller
{
    private $companiesService;
    private $systemLogsService;
    private $dealsService;
    private $clientService;

    public function __construct(CompaniesService $companiesService, SystemLogService $systemLogService, DealsService $dealsService, ClientService $clientService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->companiesService = $companiesService;
        $this->systemLogsService = $systemLogService;
        $this->dealsService = $dealsService;
        $this->clientService = $clientService;
    }

    public function processRenderCreateForm()
    {
        return View::make('crm.companies.create')->with(['dataOfClients' => $this->clientService->loadClients(true)]);
    }

    public function processViewCompanyDetails(int $companyId)
    {
        $company = $this->companiesService->loadCompany($companyId);

        if ($company) {
            return view('crm.companies.show')->with(['company' => $company]);
        } else {
            // Handle the case when the company is not found
            // You can redirect, show an error message, etc.
        }
    }

    public function processListOfCompanies()
    {
        return View::make('crm.companies.index')->with(
            [
                'companiesPaginate' => $this->companiesService->loadPagination()
            ]
        );
    }

    public function processRenderUpdateForm(int $companiesId)
    {
        return View::make('crm.companies.edit')->with(
            [
                'company' => $this->companiesService->loadCompany($companiesId),
                'clients' => ClientsModel::pluck('full_name', 'id')
            ]
        );
    }

    public function processStoreCompany(CompanyStoreRequest $request)
    {
        $storedCompanyId = $this->companiesService->execute($request->validated(), $this->getAdminId());

        if ($storedCompanyId) {
            $this->systemLogsService->loadInsertSystemLogs('CompaniesModel has been add with id: ' . $storedCompanyId, $this->systemLogsService::successCode, $this->getAdminId());
            return Redirect::to('companies')->with('message_success', $this->getMessage('messages.SuccessCompaniesStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorCompaniesStore'));
        }
    }

    public function processUpdateCompany(CompanyUpdateRequest $request, int $companiesId)
    {
        if ($this->companiesService->update($companiesId, $request->validated())) {
            return Redirect::to('companies')->with('message_success', $this->getMessage('messages.SuccessCompaniesUpdate'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorCompaniesUpdate'));
        }
    }

    public function processDeleteCompany(int $companiesId)
    {
        $dataOfCompanies = $this->companiesService->loadCompany($companiesId);
        $countDeals = $this->dealsService->loadCountAssignedDeals($companiesId);

        if ($countDeals > 0) {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.firstDeleteDeals'));
        }

        $dataOfCompanies->delete();

        $this->systemLogsService->loadInsertSystemLogs('CompaniesModel has been deleted with id: ' . $dataOfCompanies->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('companies')->with('message_success', $this->getMessage('messages.SuccessCompaniesDelete'));
    }

    public function processCompanySetIsActive(int $companiesId, bool $value)
    {
        if ($this->companiesService->loadSetActive($companiesId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('CompaniesModel has been enabled with id: ' . $companiesId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessCompaniesActive' : 'CompaniesIsNowDeactivated';

            return Redirect::to('companies')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorCompaniesActive'));
        }
    }
}
