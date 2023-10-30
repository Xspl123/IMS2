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
use App\Models\Chalan;
use App\Services\ProductsService;
use App\Services\SalesService;
use App\Services\ClientService;
use App\Services\SystemLogService;
use App\Services\InvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Models\CustomLog;
use App\Models\ProductCategory;

// use PDF;
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Mail;
use Swift_Attachment;
use Swift_Message;
use Dompdf\Options;
use App\Mail\ChallanInvoiceEmail;
use Illuminate\Support\Facades\File;
use App\Models\ProductBrand;

class SalesController extends Controller
{
    private $salesService;
    private $clientService;
    private $invoicesService;
    private $systemLogsService;
    private $productsService;

    public function __construct(ClientService $clientService,SalesService $salesService, SystemLogService $systemLogService, ProductsService $productsService, InvoicesService $invoicesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->salesService = $salesService;
        $this->clientService = $clientService;
        $this->invoicesService = $invoicesService;
        $this->systemLogsService = $systemLogService;
        $this->productsService = $productsService;
    }

    public function processRenderCreateForm()
    {
        $dataOfProducts = $this->productsService->loadProducts();
        $category_name = ProductCategory::all();
        //$dataOfCustomer = $this->clientService->loadClients();
        // $dataOfVendors = VendorModel::pluck('name', 'id');
        $dataOfVendors = DB::table('vendors')
                 ->pluck('name', 'id', 'city', 'billing_address', 'country', 'postal_code');
        $dataOfCustomer = DB::table('clients')
                 ->pluck('full_name', 'id', 'location', 'zip', 'city', 'country','phone');  
                 return View::make('crm.sales.create')->with([
                    'dataOfProducts' => $dataOfProducts,
                    'dataOfCustomer' => $dataOfCustomer,
                    'category_name' =>  $category_name,
                ]);
    }

    public function showReplaceItem() {
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
            DB::raw('DATE_FORMAT(sales.created_at, "%d %M %Y ") AS formatted_created_at'),
            DB::raw('DATE_FORMAT(sales.updated_at, "%d %M %Y ") AS formatted_updated_at')            
        )
        
        ->where('sales.status', '=', 'replacement')
        ->whereNotNull('sales.replace_remark')
        ->whereNotNull('sales.sn')
        ->whereNotNull('sales.replacement_product_sn')
        ->get();

        $dataWithPluckOfProducts = ProductsModel::pluck('name');
        return view('crm.sales.showReplaceItem', [
            'salesData' => $salesData,
            'dataWithPluckOfProducts' => $dataWithPluckOfProducts,
        ]);
    }

    public function processShowSalesDetails($saleId)
    {
        return View::make('crm.sales.show')->with([
            'sale' => $this->salesService->loadSale($saleId),
            'dataWithPluckOfProducts' => ProductsModel::pluck('name', 'id'),
        ]);
    }

    public function viewChallansDetails()
    {
        $chalanData = Chalan::all();
        return view('crm.sales.challan', [
            'chalanData' => $chalanData,
        ]);
    }
    public function challanInvoice($id)
    {
        $challanInvoice = Chalan::where('id',$id)->first();
        return view('challanInvoice',compact('challanInvoice'));
    }

    
    public function sendmailInvoice($id)
    {
        $result = sendInvoiceEmail($id);
        return view('download', [
            'pdfComWatermarkPath' => $result['pdfComWatermarkPath'],
            'pdfCustWatermarkPath' => $result['pdfCustWatermarkPath'],
        ]);
    }

    public function sendmailChallan(Request $request)
    {
        $id = $request["id"];
        $result = sendChallanEmail($id);
        return view('sachallandownload', [
            'pdfComWatermarkPathChallan' => $result['pdfComWatermarkPathChallan'],
            'pdfCustWatermarkPathChallan' => $result['pdfCustWatermarkPathChallan'],
        ]);
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
                'dataOfVendors' => DB::table('vendors')
                ->pluck('name', 'id'),
                'auth' => $auth
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to load sale: ' . $e->getMessage());
        }
    }

    public function processListOfSales()
    {
        $salesPaginate = SalesModel::orderBy('created_at', 'desc')->get();
        return View::make('crm.sales.index')->with([
            // 'sales' => $this->salesService->loadSales(),
            'salesPaginate' => $salesPaginate,
            'dataWithPluckOfProducts' => ProductsModel::pluck('name', 'id'),
        ]);
    }

    public function productUpdate($id){
        $data = SalesModel::where('id',$id)->first();
        $serial_number =  $data->sn;
         ProductsModel::where('product_serial_no',$serial_number)->update(['is_active'=>1]);
         SalesModel::where('id',$id)->update(['status'=>'Returned']);

         return Redirect::back()->with('message_success', 'product return successfully');
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

//    public function processStoreSale(SaleStoreRequest $request)
//   {
//     // Validate the request and get the validated data
//     $validatedData = $request->validated();

//     // Extract GST rate selection from the request data
//     $gstRate = $request->input('gst_rate');
    

//     // Calculate the GST amount based on the selected GST rate
//     $gstAmount = 0;

//     if ($gstRate === 'igst') {
//         $gstAmount = $validatedData['price']  * 0.18; // Assuming IGST rate is 18%
//     } elseif ($gstRate === 'sgst_cgst') {
//         $gstAmount = ($validatedData['price']  * 0.09) * 2; // Assuming SGST 9% + CGST 9%
//     } elseif ($gstRate === 'sgst') {
//         $gstAmount = $validatedData['price']  * 0.09; // Assuming SGST rate is 9%
//     } elseif ($gstRate === 'cgst') {
//         $gstAmount = $validatedData['price']  * 0.09; // Assuming CGST rate is 9%
//     }

//     $validatedData['gst_amount'] = $gstAmount;

//     // Save the sale with the GST amount and total amount
//     $storedSaleId = $this->salesService->execute($validatedData, $this->getAdminId());

//     if ($storedSaleId) {
//         // Create and save the invoice data
//         $invoiceData = [
//             'sale_id' => $storedSaleId,
//             'invoice_number' => '#Xeno/Up23-24/' . $storedSaleId, // generate a unique invoice number based on your requirements
//             'invoice_date' => date('Y-m-d'), // Current date as the invoice date
//             'customer_details' => $validatedData['name'] ?? 'N/A', // For simplicity, we're using the rent name as customer details
//             'subtotal' => $validatedData['total_amount'] - $gstAmount,
//             'tax' => $gstAmount,
//             'grand_total' => $validatedData['total_amount'],
//         ];
        
//         $storedInvoiceId = $this->invoicesService->execute($invoiceData, $this->getAdminId());

//        // if ($storedInvoiceId) {
//             // Decrease the product count after successfully saving the rent and invoice
//             // $product = ProductsModel::find($validatedData['product_id']);

//             // if ($product) {
//             //     $quantityRented = $validatedData['quantity'];
//             //     $product->count -= $quantityRented;
//             //     $product->save();
//             // }
            
//     //         $this->systemLogsService->loadInsertSystemLogs('SalesModel has been added with id: ' . $storedSaleId, $this->systemLogsService::successCode, $this->getAdminId());
//     //         return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
//     if ($storedInvoiceId) {
//         // Find the product by ID
//         $product = ProductsModel::find($validatedData['product_id']);
    
//         if ($product) {
//             // Check if the product is active (is_active is 1)
//             if ($product->is_active == 1) {
//                 // Decrease the product count
//                 $product->count--;
    
//                 // Check if the product count is zero or less
//                 if ($product->count <= 0) {
//                     // Set the product as inactive (deactivate)
//                     $product->is_active = 0;
//                 }
    
//                 // Save the product
//                 $product->save();
    
//                 // Store the sale and return a success message
//                 $this->systemLogsService->loadInsertSystemLogs('SalesModel has been added with id: ' . $storedSaleId, $this->systemLogsService::successCode, $this->getAdminId());
//                 return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
//             } else {
//                 // Product is deactivated, display a message
//                 // Add debugging statement or log message here to check if this block is executed
//                 return Redirect::to('sales')->with('message_error', 'Product is deactivated. Sale not allowed.');
//             }
//         } else {
//             // Product not found, display an error message or log it
//             // Add debugging statement or log message here to check if this block is executed
//             return Redirect::to('sales')->with('message_error', 'Product not found. Sale not allowed.');
//         }
//     }
//     }    
    

//     return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorSalesStore'));
//   }
    // public function processStoreSale(SaleStoreRequest $request)
    // {
    //     //dd($request->all());
    //     // Validate the request and get the validated data
    //     $validatedData = $request->validated();

    //     // Extract GST rate selection from the request data
    //     $gstRate = $request->input('gst_rate');

    //     // Calculate the GST amount based on the selected GST rate
    //     $gstAmount = 0;

    //     if ($gstRate === 'igst') {
    //         $gstAmount = $validatedData['sale_price'] * 0.18; // Assuming IGST rate is 18%
    //     } elseif ($gstRate === 'sgst_cgst') {
    //         $gstAmount = ($validatedData['sale_price'] * 0.09) * 2; // Assuming SGST 9% + CGST 9%
    //     } elseif ($gstRate === 'sgst') {
    //         $gstAmount = $validatedData['sale_price'] * 0.09; // Assuming SGST rate is 9%
    //     } elseif ($gstRate === 'cgst') {
    //         $gstAmount = $validatedData['sale_price'] * 0.09; // Assuming CGST rate is 9%
    //     }

    //     $validatedData['gst_amount'] = $gstAmount;

    //     // Find the product by ID
    //     $product = ProductsModel::find($validatedData['product_id']);

    //     if ($product) {
    //         // Check if the product is active (is_active is 1)
    //         if ($product->is_active == 1) {
    //             // Save the sale with the GST amount and total amount
    //             $storedSaleId = $this->salesService->execute($validatedData, $this->getAdminId());
    //           // Log the action with additional data
    //             Log::channel('custom_sales_log')->info('Sale stored successfully', ['action' => 'StoreSale', 'data' => $validatedData]);
    //             if ($storedSaleId) {
    //                 // Create and save the invoice data
    //                 $invoiceData = [
    //                     'sale_id' => $storedSaleId,
    //                     'invoice_number' => '#Xeno/Up23-24/' . $storedSaleId, // generate a unique invoice number based on your requirements
    //                     'invoice_date' => date('Y-m-d'), // Current date as the invoice date
    //                     'customer_details' => $validatedData['name'] ?? 'N/A', // For simplicity, we're using the rent name as customer details
    //                     'subtotal' => $validatedData['total_amount'] - $gstAmount,
    //                     'tax' => $gstAmount,
    //                     'grand_total' => $validatedData['total_amount'],
    //                 ];

    //                 $storedInvoiceId = $this->invoicesService->execute($invoiceData, $this->getAdminId());

    //                 if ($storedInvoiceId) {
    //                     // Update the product's is_active field to 0 (deactivate)
    //                     $product->is_active = 0;
    //                     $product->save();

    //                     // Log success message
    //                     Log::info('Sale stored successfully.');

    //                     // Store the sale and return a success message
    //                     return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
    //                         }
    //                     }
    //                         } else {
    //                             // Product is deactivated, log and display an error message
    //                             Log::error('Product is deactivated. Sale not allowed.');
    //                             return Redirect::to('sales')->with('message_danger', 'Product is deactivated. Sale not allowed.');
    //                         }
    //                     } else {
    //                         // Product not found, log and display an error message
    //                         Log::error('Product not found. Sale not allowed.');
    //                         return Redirect::to('sales')->with('message_danger', 'Product not found. Sale not allowed.');
    //                     }

    //                     // Sale storage failed, log and display an error message
    //                     Log::error('Sale storage failed.');
    //                     return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorSalesStore'));
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
            $gstAmount = $validatedData['sale_price'] * 0.18; // Assuming IGST rate is 18%
        } elseif ($gstRate === 'sgst_cgst') {
            $gstAmount = ($validatedData['sale_price'] * 0.09) * 2; // Assuming SGST 9% + CGST 9%
        } elseif ($gstRate === 'sgst') {
            $gstAmount = $validatedData['sale_price'] * 0.09; // Assuming SGST rate is 9%
        } elseif ($gstRate === 'cgst') {
            $gstAmount = $validatedData['sale_price'] * 0.09; // Assuming CGST rate is 9%
        }

        $validatedData['gst_amount'] = $gstAmount;

        // Find the product by ID
        $product = ProductsModel::find($validatedData['product_id']);

        if ($product) {
            // Check if the product is active (is_active is 1)
            if ($product->is_active == 1) {
                // Save the sale with the GST amount and total amount
                $storedSaleId = $this->salesService->execute($validatedData, $this->getAdminId());

               // Use the customLog helper function to log and insert data
                    $message = 'Sale has been added'. $storedSaleId;
                    $logData = ['data' => $validatedData];
                    $userId = Auth::id();
                    $ipAddress = $request->ip();
                    customLog($message, $logData, $userId, $ipAddress); 
                    //end customLog helper function
                
                if ($storedSaleId) {
                    $message = 'Sale has been added with ID ' . $storedSaleId . ' - ' . json_encode($validatedData);
                    $this->systemLogsService->loadInsertSystemLogs($message, $this->systemLogsService::successCode, $this->getAdminId());
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
                        
                        // Update the product's is_active field to 0 (deactivate)
                        $product->is_active = 0;
                        $product->save();

                        // Log success message
                        Log::info('Sale stored successfully');

                        // Store the sale and return a success message
                        return Redirect::to('sales')->with('message_success', $this->getMessage('messages.SuccessSalesStore'));
                    }
                }
            } else {
                // Product is deactivated, log and display an error message
                Log::error('Product is deactivated. Sale not allowed');
                return Redirect::to('sales')->with('message_danger', 'Product is deactivated. Sale not allowed');
            }
        } else {
            // Product not found, log and display an error message
            Log::error('Product not found. Sale not allowed');
            return Redirect::to('sales')->with('message_danger', 'Product not found. Sale not allowed');
        }

        // Sale storage failed, log and display an error message
        Log::error('Sale storage failed');
        return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorSalesStore'));
    }


        public function showInvoice($id)
        {
            $invoice = InvoiceModel::where('sale_id', $id)->first();
        
            if ($invoice) {
                $sale_id = $invoice->sale_id;
        
                $sales = DB::table('sales')
                    ->select('quantity', 'gst_rate')
                    ->where('id', $sale_id)
                    ->get();
        
                // Pass the invoice and sales data to the view
                return view('crm.sales.invoice', compact('invoice', 'sales'));
            } else {
                // Handle the case where no invoice was found for the given $id
                return redirect()->back()->with('error', 'Invoice not found');
            }
        }
    
        public function processUpdateSale(SaleUpdateRequest $request, int $saleId)
        {
            try {
                // Find the sale record to update
                $sale = $this->salesService->find($saleId);
    
                if (!$sale) {
                    return redirect()->back()->with('message_danger', 'Sale not found.');
                }
    
                $requestData = $request->validated();
    
                // Update the sale
                $updatedSale = $this->salesService->updateSale($saleId, $requestData);
    
                $data = SalesModel::where('id', $saleId)->first(); // Corrected the variable name $id to $saleId
                $serial_number = $data->sn;
    
                if ($requestData['status'] == 'return') {
                    ProductsModel::where('product_serial_no', $serial_number)->update(['is_active' => 1]);
                    SalesModel::where('id', $saleId)->update(['status' => 'Returned']);
                }
    
                // Generate a random 4-digit challan number
                $challanNumber = mt_rand(1000, 9999);
    
                // Prepare the data for creating or updating the chalan entry
                $chalanData = [
                    'sale_id' => $saleId,
                    'challan_no' => '#Xeno/Up23-24/' . $challanNumber,
                    'replacement_Remark' => $requestData['replace_remark'],
                    'replacement_product_item' => $requestData['replacement_with'],
                    'replacement_to_custmor' => $requestData['replacement_to'],
                    'replacement_product_serial' => $requestData['replacement_product_sn'],
                    'replacement_product_vendor' => $requestData['replacement_product_vendor'],
                    'defulty_product_name' => $requestData['defulty_product_name'],
                    'defulty_product_sn' => $requestData['defulty_product_sn'],
                    'defulty_product_vendor' => $requestData['defulty_product_vendor'],
                    'defulty_product_remark' => $requestData['defulty_product_remark'],
                    'approved_by' => $requestData['approved_by'],
                ];
    
                // Create or update the chalan entry
                Chalan::updateOrInsert(['sale_id' => $saleId], $chalanData);
    
                // Manually update the updated_at timestamp for the chalan
                $chalan = Chalan::where('sale_id', $saleId)->first();
                if ($chalan) {
                    $chalan->touch(); // This will update the updated_at timestamp
                }
    
                return Redirect::to('sales')->with('message_success', 'Sale updated successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('message_danger', 'Failed to update sale: ' . $e->getMessage());
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

    public function downloadCompanyPDF()
    {
        $pdfComWatermarkPath = storage_path('app/tmp/invoice_com_watermark.pdf');
        return response()->download($pdfComWatermarkPath, 'invoice_com_watermark.pdf');
    }

    public function downloadCustomerPDF()
    {
        $pdfCustWatermarkPath = storage_path('app/tmp/invoice_cust_watermark.pdf');
        return response()->download($pdfCustWatermarkPath, 'invoice_cust_watermark.pdf');
    }

        // for sale challan
        public function challanDownloadCompanyPDF()
        {
            $pdfComWatermarkPath = storage_path('app/tmp/challan_com_watermark.pdf');
            return response()->download($pdfComWatermarkPath, 'challan_com_watermark.pdf');
        }
        
        public function challanDownloadCustomerPDF()
        {
            $pdfCustWatermarkPath = storage_path('app/tmp/challan_cust_watermark.pdf');
            return response()->download($pdfCustWatermarkPath, 'challan_cust_watermark.pdf');
        }


    public function getProductName(Request $request)
    {
        $getproduct = ProductsModel::where('product_category_id', $request->category_id)
        ->where('is_active', 1) // Assuming 1 represents active products
        ->get();
            $data = array(
            'status'=>'success',
            'getproducts' =>$getproduct,
        );
        return $data;
    }

    // sale challans

    // $companyRecipient = 'abhkumar17@gmail.com';
    //     $customerRecipient = 'abhkumar17@gmail.com';
    //     $result = sendChallanEmail($id, $companyRecipient, $customerRecipient);
    //     return view('sachallandownload', [
    //         'pdfComWatermarkPath' => $result['pdfComWatermarkPath'],
    //         'pdfCustWatermarkPath' => $result['pdfCustWatermarkPath'],
    //     ]);

    public function saleChallanCreate($id)
    {
        $challanInvoice = SalesModel::where('id',$id)->first();
        return view('saleChallanCreate',compact('challanInvoice'));
        
    }

    public function saleChallanDetails()
    {
        $challanInvoice = SalesModel::all();
        return view('crm.sales.challan_create',compact('challanInvoice'));
        
    }

    public function getData(Request $request)
    {
        $status = $request->status;
        $relatedData = SalesModel::where('status', 'like', '%' . $status . '%')->get();
        return view('related-data', ['relatedData' => $relatedData, 'status' => $status]);
    }

}