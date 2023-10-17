<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorStoreRequest;
use App\Http\Requests\VendorUpdateRequest;
use App\Services\VendorsService;
use App\Services\DealsService;
use App\Services\ClientService;
use App\Services\SystemLogService;
use App\Models\ClientsModel;
use View;
use Illuminate\Support\Facades\Redirect;

class VendorsController extends Controller
{
    private $vendorsService;
    private $systemLogsService;
    private $dealsService;
    private $clientService;

    public function __construct(VendorsService $vendorsService, SystemLogService $systemLogService, DealsService $dealsService, ClientService $clientService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->vendorsService = $vendorsService;
        $this->systemLogsService = $systemLogService;
        $this->dealsService = $dealsService;
        $this->clientService = $clientService;
    }

    public function processRenderCreateForm()
    {
        return View::make('crm.vendors.create');
    }

    public function processViewVendorDetails(int $vendorId)
    {
        $vendor = $this->vendorsService->loadVendor($vendorId);

        if ($vendor) {
            return view('crm.vendors.show')->with(['vendor' => $vendor]);
        } else {
            // Handle the case when the company is not found
            // You can redirect, show an error message, etc.
        }
    }

    public function processListOfVendors()
    {
        return View::make('crm.vendors.index')->with(
            [
                'vendorsPaginate' => $this->vendorsService->loadPagination()
            ]
        );
    }

    public function processRenderUpdateForm(int $vendorsId)
    {
        return View::make('crm.vendors.edit')->with(
            [
                'vendor' => $this->vendorsService->loadVendor($vendorsId),
                // 'clients' => ClientsModel::pluck('full_name', 'id')
            ]
        );
    }

    public function processStoreVendor(VendorStoreRequest $request)
    {
        $storedVendorId = $this->vendorsService->execute($request->validated(), $this->getAdminId());

        if ($storedVendorId) {
            $message = 'Vendor has been added with ID ' . $storedVendorId . ' - ' . json_encode($request->validated());
            $this->systemLogsService->loadInsertSystemLogs($message, $this->systemLogsService::successCode, $this->getAdminId());            
            return Redirect::to('vendors')->with('message_success', $this->getMessage('messages.SuccessVendorsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorVendorsStore'));
        }
    }

    public function processUpdateVendor(VendorUpdateRequest $request, int $vendorsId)
    {
        if ($this->vendorsService->update($vendorsId, $request->validated())) {
            return Redirect::to('vendors')->with('message_success', $this->getMessage('messages.SuccessVendorsUpdate'));
        } else {
            return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorVendorsUpdate'));
        }
    }

    public function processDeleteVendor(int $vendorsId)
    {
        $dataOfVendors = $this->vendorsService->loadVendor($vendorsId);
        $countDeals = $this->dealsService->loadCountAssignedDeals($vendorsId);

        if ($countDeals > 0) {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.firstDeleteDeals'));
        }

        $dataOfVendors->delete();

        $this->systemLogsService->loadInsertSystemLogs('VendorModel has been deleted with id: ' . $dataOfVendors->id, $this->systemLogsService::successCode, $this->getAdminId());

        return Redirect::to('vendors')->with('message_success', $this->getMessage('messages.SuccessVendorsDelete'));
    }

    public function processVendorSetIsActive(int $vendorsId, bool $value)
    {
        if ($this->vendorsService->loadSetActive($vendorsId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('VendorModel has been enabled with id: ' . $vendorsId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessVendorsActive' : 'VendorsIsNowDeactivated';

            return Redirect::to('vendors')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorVendorsActive'));
        }
    }
}
