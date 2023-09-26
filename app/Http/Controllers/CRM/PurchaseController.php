<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;
use App\Models\ProductsModel;
use App\Models\PurchaseModel;
use App\Services\ProductsService;
use App\Services\PurchaseService;
use App\Services\SystemLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class PurchaseController extends Controller
{
    private $purchaseService;
    private $systemLogsService;
    private $productsService;

    public function __construct(PurchaseService $purchaseService, SystemLogService $systemLogService, ProductsService $productsService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->purchaseService = $purchaseService;
        $this->systemLogsService = $systemLogService;
        $this->productsService = $productsService;
    }

    public function processRenderCreateForm()
    {
        return View::make('crm.purchase.create')->with(['dataOfProducts' => $this->productsService->loadProducts()]);
    }

    public function processShowPurchaseDetails($purchaseId)

    {
        // echo $purchaseId;exit;
        return View::make('crm.purchase.show')->with(['purchase' => $this->purchaseService->loadPurchase($purchaseId)]);
    }

    public function processRenderUpdateForm(Request $request, $saleId)
    {
        $requestData = $request->all();

        try {
            $sale = $this->purchaseService->find($saleId);

            return View::make('crm.purchase.edit')->with([
                'sale' => $sale,
                'dataWithPluckOfProducts' => ProductsModel::pluck('name', 'id')
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to load sale: ' . $e->getMessage());
        }
    }

    public function processListOfPurchase()
    {
        return View::make('crm.purchase.index')->with([
            'sales' => $this->purchaseService->loadSales(),
            'salesPaginate' => $this->purchaseService->loadPaginate()
        ]);
    }

    // public function processStorePurchase(PurchaseStoreRequest $request)
    // {
    //     $validatedData = $request->validated();
    //     $adminId = $this->getAdminId();
    //     $validatedData['admin_id'] = $adminId; // Add admin_id to the validated data

    //     $storedPurchaseId = $this->purchaseService->execute($validatedData, $adminId);

    //     if ($storedPurchaseId) {
    //         $logMessage = 'PurchaseModel has been added with id: ' . $storedPurchaseId;
    //         $this->systemLogsService->loadInsertSystemLogs($logMessage, $this->systemLogsService::successCode, $adminId);

    //         return redirect('purchase/purchase')->with('message_success', $this->getMessage('messages.SuccessPurchaseStore'));
    //     } else {
    //         return redirect()->back()->with('message_error', $this->getMessage('messages.ErrorPurchaseStore'));
    //     }
    // }
    public function processStorePurchase(PurchaseStoreRequest $request)
{
    $validatedData = $request->validated();
    $adminId = $this->getAdminId();
    $validatedData['admin_id'] = $adminId; // Add admin_id to the validated data

    // Assuming the "gst_rate" field is present in the form and properly submitted
    $gstRate = isset($validatedData['gst_rate']) ? (float) $validatedData['gst_rate'] : 0;

    // Calculate the total price with GST
    $price = isset($validatedData['price']) ? (float) $validatedData['price'] : 0;
    $quantity = isset($validatedData['quantity']) ? (int) $validatedData['quantity'] : 0;
    $totalPrice = $price * $quantity * (1 + ($gstRate / 100));
    $validatedData['total_price'] = number_format($totalPrice, 2, '.', ''); // Remove the comma

    $storedPurchase = $this->purchaseService->execute($validatedData, $adminId);

    if ($storedPurchase && is_object($storedPurchase) && property_exists($storedPurchase, 'id')) {
        $logMessage = 'PurchaseModel has been added with id: ' . $storedPurchase->id;
        $this->systemLogsService->loadInsertSystemLogs($logMessage, $this->systemLogsService::successCode, $adminId);

        return redirect('purchase/purchase')->with('message_success', $this->getMessage('messages.SuccessPurchaseStore'));
    } else {
        return redirect()->back()->with('message_error', $this->getMessage('messages.ErrorPurchaseStore'));
    }
}

    
    


//     public function processStorePurchase(PurchaseStoreRequest $request)
// {
//     $validatedData = $request->validated();
//     $adminId = $this->getAdminId();
//     $validatedData['admin_id'] = $adminId; // Add admin_id to the validated data

//     $storedPurchaseId = $this->purchaseService->execute($validatedData, $adminId);

//     if ($storedPurchaseId) {
//         // Calculate purchase profit (if needed)
//         $purchaseProfit = 0; // No profit for purchases

//         // Calculate sale profit (if available)
//         $saleProfit = $this->calculateSaleProfit($validatedData);

//         // Calculate difference in profit
//         $profitDifference = $saleProfit - $purchaseProfit;

//         // Perform any additional actions or log the profit difference
//         // For example:
//         $this->logProfitDifference($storedPurchaseId, $profitDifference, $adminId);

//         return redirect('purchase/purchase')->with('message_success', $this->getMessage('messages.SuccessPurchaseStore'));
//     } else {
//         return redirect()->back()->with('message_error', $this->getMessage('messages.ErrorPurchaseStore'));
//     }
// }

private function calculateSaleProfit(array $validatedData): float
{
    // Implement your logic to calculate sale profit based on the validated data
    // For example:
    $quantity = $validatedData['quantity'];
    $price = $validatedData['price'];

    return $quantity * $price;
}

private function logProfitDifference(int $purchaseId, float $profitDifference, int $adminId)
{
    // Implement your logic to log the profit difference
    // For example:
    $logMessage = 'Profit difference for purchase ' . $purchaseId . ': ' . $profitDifference;
    $this->systemLogsService->loadInsertSystemLogs($logMessage, $this->systemLogsService::successCode, $adminId);
}




public function processUpdatePurchase(PurchaseUpdateRequest $request, int $purchaseId)
{
    try {
        $purchase = $this->purchaseService->find($purchaseId);
        $requestData = $request->validated();

        $updatedPurchase = $this->purchaseService->updatePurchase($purchaseId, $requestData);

        return redirect('purchase/purchase')->with('message_success', 'Purchase updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('message_danger', 'Failed to update purchase: ' . $e->getMessage());
    }
}

    public function processDeletePurchase(int $purchaseId)
    {
        $purchasesDetails = $this->purchaseService->loadPurchase($purchaseId);
        $purchasesDetails->delete();

        return Redirect::to('purchase/purchase')->with('message_success', 'Purchase item deleted successfully.');
    }

    public function processPurchaseSetIsActive(int $purchasId, bool $value)
    {
        if ($this->purchaseService->loadIsActive($purchasId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('SalesModel has been enabled with id: ' . $purchasId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessPurchaseActive' : 'PurchaseIsNowDeactivated';

            return Redirect::to('purchase/purchase')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorPurchaseActive'));
        }
    }
}
