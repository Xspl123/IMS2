<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Services\ClientService;
use App\Services\EmployeesService;
use App\Services\SystemLogService;
use App\Http\Controllers\Controller;
use App\Models\ClientsModel;
use http\Client;
use View;
use Illuminate\Support\Facades\Redirect;

class EmployeesController extends Controller
{
    private  $employeesService;
    private  $systemLogsService;
    private  $clientService;

    public function __construct(EmployeesService $employeesService, SystemLogService $systemLogService, ClientService $clientService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->employeesService = $employeesService;
        $this->systemLogsService = $systemLogService;
        $this->clientService = $clientService;
    }

    public function processRenderCreateForm()
    {
        return View::make('crm.employees.create')->with(['dataOfClients' => $this->clientService->loadClients(true)]);
    }

    public function processShowEmployeeDetails($employeeId)
    {
        return View::make('crm.employees.show')->with(['employee' => $this->employeesService->loadEmployeeDetails($employeeId)]);
    }

    public function processListOfEmployees()
    {
        return View::make('crm.employees.index')->with(
            [
                'employeesPaginate' => $this->employeesService->loadPaginate()
            ]);
    }

    public function processRenderUpdateForm($employeeId)
    {
        return View::make('crm.employees.edit')->with(
            [
                'employee' => $this->employeesService->loadEmployeeDetails($employeeId),
                'clients' => ClientsModel::pluck('full_name', 'id')
            ]
        );
    }

    public function processStoreEmployee(EmployeeStoreRequest $request)
    {
        $storedEmployeeId = $this->employeesService->execute($request->validated(), $this->getAdminId());

        if ($storedEmployeeId) {
            $this->systemLogsService->loadInsertSystemLogs('Employees has been add with id: ' . $storedEmployeeId, $this->systemLogsService::successCode, $this->getAdminId());
            return Redirect::to('employees')->with('message_success', $this->getMessage('messages.SuccessEmployeesStore'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorEmployeesStore'));
        }
    }

    public function processUpdateEmployee(EmployeeUpdateRequest $request, int $employeeId)
    {
        if ($this->employeesService->update($employeeId, $request->validated())) {
            return Redirect::to('employees')->with('message_success', $this->getMessage('messages.SuccessEmployeesUpdate'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorEmployeesUpdate'));
        }
    }

    public function processDeleteEmployee($employeeId)
    {
        $dataOfEmployees = $this->employeesService->loadEmployeeDetails($employeeId);
        $countTasks = $this->employeesService->countEmployeeTasks($dataOfEmployees);

        if ($countTasks > 0) {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.firstDeleteTasks'));
        }

        $dataOfEmployees->delete();

        $this->systemLogsService->loadInsertSystemLogs('Employees has been deleted with id: ' . $dataOfEmployees->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('employees')->with('message_success', $this->getMessage('messages.SuccessEmployeesDelete'));
    }

    public function processEmployeeSetIsActive($employeeId, $value)
    {
        if ($this->employeesService->loadSetActive($employeeId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('Employees has been enabled with id: ' . $employeeId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessEmployeesActive' : 'EmployeesIsNowDeactivated';

            return Redirect::to('employees')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorEmployeesActive'));
        }
    }
}

