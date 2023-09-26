<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaleStoreRequest;
use App\Http\Requests\SaleUpdateRequest;
use App\Models\ProductsModel;
use App\Models\InvoiceModel;
use App\Models\SalesModel;
use App\Models\VendorModel;
use App\Services\ProductsService;
use App\Services\SalesService;
use App\Services\SystemLogService;
use App\Services\InvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class SalesController extends Controller
{
    private $salesService;
    private $invoicesService;
    private $systemLogsService;
    private $productsService;

    public function __construct(SalesService $salesService, SystemLogService $systemLogService, ProductsService $productsService, InvoicesService $invoicesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->salesService = $salesService;
        $this->invoicesService = $invoicesService;
        $this->systemLogsService = $systemLogService;
        $this->productsService = $productsService;
    }

    public function processRenderCreateForm()
    {
        // $dataOfVendors = VendorModel::pluck('name', 'id');
        $dataOfVendors = DB::table('vendors')
                 ->pluck('name', 'id', 'city', 'billing_address', 'country', 'postal_code');
        $dataOfCustomer = DB::table('clients')
                 ->pluck('full_name', 'id', 'location', 'zip', 'city', 'country','phone');         
            return View::make('crm.sales.create')->with([
            'dataOfProducts' => $this->productsService->loadProducts(),
            'dataOfVendors' => $dataOfVendors,
            'dataOfCustomer' => $dataOfCustomer
        
        ]);
    }

    public function showReplaceItem() {
        // Sales data retrieve karen
        $salesData = DB::table('sales')
        ->select(
            DB::raw('(SELECT full_name FROM clients WHERE id = sales.replacement_to) AS full_name'),
            'sales.replacement_to',
            'sales.replace_remark',
            'sales.name',
            'sales.status',
            'sales.sn',
            'sales.replacement_product_sn',
            'sales.approved_by',
            DB::raw('DATE_FORMAT(sales.created_at, "%d %M %Y %H:%i") AS formatted_created_at'),
            DB::raw('DATE_FORMAT(sales.updated_at, "%d %M %Y %H:%i") AS formatted_updated_at')            
        )
        
        ->where('sales.status', '=', 'replacement')
        ->whereNotNull('sales.replace_remark')
        ->where('sales.replacement_product_sn', '>', 0)
        ->where('sales.sn', '>', 0)
        ->get();

        // ProductsModel se product data retrieve karen
        $dataWithPluckOfProducts = ProductsModel::pluck('name');
        
        // Clients table se customer data retrieve karen
        $dataOfCustomer = DB::table('clients')
            ->pluck('full_name', 'id');
    
        // View mein data pass karen
        return view('crm.sales.showReplaceItem', [
            'salesData' => $salesData,
            'dataWithPluckOfProducts' => $dataWithPluckOfProducts,
            'dataOfCustomer' => $dataOfCustomer,
        ]);
    }
    
    
    
    
    
    

    public function processShowSalesDetails($saleId)
    {
        return View::make('crm.sales.show')->with(['sale' => $this->salesService->loadSale($saleId)]);
    }

    public function processRenderUpdateForm(Request $request, $saleId)
    {
        $auth = auth()->user()->name;
        
        $requestData = $request->all();

        try {
            $sale = $this->salesService->find($saleId);

            return View::make('crm.sales.edit')->with([
                'sale' => $sale,
                'dataWithPluckOfProducts' => ProductsModel::pluck('name', 'id'),
                'dataOfCustomer' => DB::table('clients')
                ->pluck('full_name', 'id'),
                'auth' => $auth
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to load sale: ' . $e->getMessage());
        }
    }

    public function processListOfSales()
    {
        return View::make('crm.sales.index')->with([
            'sales' => $this->salesService->loadSales(),
            'salesPaginate' => $this->salesService->loadPaginate()
        ]);
    }

    // public function processStoreSale(SaleStoreRequest $request)
    // {
    //     $storedSaleId = $this->salesService->execute($request->validated(), $this->getAdminId());

    //     if ($storedSaleId) {
    //         $this->systemLogsService->loadInsertSystemLogs('SalesModel has been add with id: ' . $storedSaleId, $this->systemLogsService::successCode, $this->getAdminId());
    //         return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
    //     } else {
    //         return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorSalesStore'));
    //     }
    // }
    // public function processStoreSale(SaleStoreRequest $request)
    // {
    //     // Validate the request and get the validated data
    //     $validatedData = $request->validated();
    
    //     // Extract GST rate selection from the request data
    //     $gstRate = $request->input('gst_rate');
    
    //     // Calculate GST amount based on the selected rate and quantity
    //     $quantity = (int) $validatedData['quantity'];
    //     $price = (float) $validatedData['price'];
    //     $gstAmount = 0;
    
    //     if ($gstRate === 'igst') {
    //         $gstAmount = $price * $quantity * 0.18; // IGST 18%
    //     } elseif ($gstRate === 'sgst_cgst') {
    //         $gstAmount = ($price * $quantity * 0.09) * 2; // SGST 9% + CGST 9%
    //     }
    
    //     // Calculate the total amount including GST
    //     $totalAmount = ($price * $quantity) + $gstAmount;
    
    //     // Add the calculated GST amount and total amount to the validated data
    //     $validatedData['gst_amount'] = $gstAmount;
    //     $validatedData['total_amount'] = $totalAmount;
    
    //     // Save the sale with the GST amount and total amount
    //     $storedSaleId = $this->salesService->execute($validatedData, $this->getAdminId());
    
    //     if ($storedSaleId) {
    //         $this->systemLogsService->loadInsertSystemLogs('SalesModel has been added with id: ' . $storedSaleId, $this->systemLogsService::successCode, $this->getAdminId());
    //         return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
    //     } else {
    //         return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorSalesStore'));
    //     }
    // }

   public function processStoreSale(SaleStoreRequest $request)
  {
    // Validate the request and get the validated data
    $validatedData = $request->validated();

    // Extract GST rate selection from the request data
    $gstRate = $request->input('gst_rate');
    

    // Calculate the GST amount based on the selected GST rate
    $gstAmount = 0;

    if ($gstRate === 'igst') {
        $gstAmount = $validatedData['price'] * $validatedData['quantity'] * 0.18; // Assuming IGST rate is 18%
    } elseif ($gstRate === 'sgst_cgst') {
        $gstAmount = ($validatedData['price'] * $validatedData['quantity'] * 0.09) * 2; // Assuming SGST 9% + CGST 9%
    } elseif ($gstRate === 'sgst') {
        $gstAmount = $validatedData['price'] * $validatedData['quantity'] * 0.09; // Assuming SGST rate is 9%
    } elseif ($gstRate === 'cgst') {
        $gstAmount = $validatedData['price'] * $validatedData['quantity'] * 0.09; // Assuming CGST rate is 9%
    }

    $validatedData['gst_amount'] = $gstAmount;

    // Save the sale with the GST amount and total amount
    $storedSaleId = $this->salesService->execute($validatedData, $this->getAdminId());

    if ($storedSaleId) {
        // Create and save the invoice data
        $invoiceData = [
            'sale_id' => $storedSaleId,
            'invoice_number' => '#Xeno/Up23-24/' . $storedSaleId, // generate a unique invoice number based on your requirements
            'invoice_date' => date('Y-m-d'), // Current date as the invoice date
            'customer_details' => $validatedData['name'] ?? 'N/A', // For simplicity, we're using the rent name as customer details
            'subtotal' => $validatedData['total_amount'] - $gstAmount,
            'tax' => $gstAmount,
            'grand_total' => $validatedData['total_amount'],
        ];
        
        $storedInvoiceId = $this->invoicesService->execute($invoiceData, $this->getAdminId());

        if ($storedInvoiceId) {
            // Decrease the product count after successfully saving the rent and invoice
            $product = ProductsModel::find($validatedData['product_id']);

            if ($product) {
                $quantityRented = $validatedData['quantity'];
                $product->count -= $quantityRented;
                $product->save();
            }
            
            $this->systemLogsService->loadInsertSystemLogs('SalesModel has been added with id: ' . $storedSaleId, $this->systemLogsService::successCode, $this->getAdminId());
            return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
        }
    }

    return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorSalesStore'));
  }


  public function showInvoice($id)
  {
      $invoice = InvoiceModel::where('sale_id',$id)->first();

      $sale_id = $invoice->sale_id;

      $sales = DB::table('sales')->select('quantity','gst_rate')->where('id',$sale_id )->get();

      // Pass the invoice and rent data to the view
      return View::make('crm.sales.invoice', compact('invoice','sales'));
  }

//   public function showSales($id)
//     {
//         $sale = SalesModel::with('vendor_id')->findOrFail($id);
//         print_r($sale);exit;

//         return view('your-view-name', compact('sale'));
//     }
     
    // public function processStoreSale(SaleStoreRequest $request)
    // {
    //     $storedSaleId = $this->salesService->execute($request->validated(), $this->getAdminId());

    //     if ($storedSaleId) {
    //         // Calculate sale profit
    //         $saleProfit = $this->calculateSaleProfit($request->validated());

    //         // Calculate purchase profit (if available)
    //         $purchaseProfit = $this->calculatePurchaseProfit($storedSaleId);

    //         // Calculate difference in profit
    //         $profitDifference = $saleProfit - $purchaseProfit;

    //         // Perform any additional actions or log the profit difference
    //         // For example:
    //         $this->logProfitDifference($storedSaleId, $profitDifference, $this->getAdminId());

    //         return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
    //     } else {
    //         return Redirect::back()->with('message_success', $this->getMessage('messages.ErrorSalesStore'));
    //     }
    // }

    // private function calculateSaleProfit(array $validatedData): float
    // {
    //     // Implement your logic to calculate sale profit based on the validated data
    //     // For example:
    //     $quantity = $validatedData['quantity'];
    //     $price = $validatedData['price'];

    //     return $quantity * $price;
    // }

    // private function calculatePurchaseProfit(int $saleId): float
    // {
    //     // Implement your logic to calculate purchase profit based on the sale ID
    //     // For example:
    //     $sale = $this->salesModel->getSale($saleId);

    //     return $sale->quantity * $sale->price;
    // }

    // private function logProfitDifference(int $saleId, float $profitDifference, int $adminId)
    // {
    //     // Implement your logic to log the profit difference
    //     // For example:
    //     $logMessage = 'Profit difference for sale ' . $saleId . ': ' . $profitDifference;
    //     $this->systemLogsService->loadInsertSystemLogs($logMessage, $this->systemLogsService::successCode, $adminId);
    // }


    public function processUpdateSale(SaleUpdateRequest $request, int $saleId)
    {
        try {
            $sale = $this->salesService->find($saleId);
            $requestData = $request->validated();

            $updatedSale = $this->salesService->updateSale($saleId, $requestData);

            return Redirect::to('sales')->with('message_success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to update sale: ' . $e->getMessage());
        }
    }

    public function processDeleteSale(int $saleId)
    {
        $salesDetails = $this->salesService->loadSale($saleId);
        $salesDetails->delete();

        return Redirect::to('sales')->with('message_success', 'Sale deleted successfully.');
    }

    public function processSaleSetIsActive(int $saleId, bool $value)
    {
        if ($this->salesService->loadIsActive($saleId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('SalesModel has been enabled with id: ' . $saleId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessSalesActive' : 'SalesIsNowDeactivated';

            return Redirect::to('sales')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorSalesActive'));
        }
    }
}
