<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Services\ProductsService;
use App\Services\SystemLogService;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\ProductsModel;
use App\Models\ProductBrand;
use App\Models\VendorModel;
use App\Models\ProductCategory;
use App\Models\CustomLog;
use GeoIp2\Database\Reader;
use Log;


use View;
use Illuminate\Http\Request;


class ProductsController extends Controller
{
    private  $productsService;
    private  $systemLogsService;
    
    public function __construct(ProductsService $productsService, SystemLogService $systemLogService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->productsService = $productsService;
        $this->systemLogsService = $systemLogService;
    }

    public function processRenderCreateForm()
    {
       // Fetch only the names of clients to use in the dropdown select
       $dataOfVendors = VendorModel::pluck('name','id');
       $getbrands =  ProductBrand::getbrands();
       $product_cat =  ProductCategory::get();

        return view('crm.products.create', compact('dataOfVendors','getbrands','product_cat'));
    }

    

    public function processShowProductsDetails(int $productId)
    {
        return View::make('crm.products.show')->with(['product' => $this->productsService->loadProduct($productId)]);
    }


    public function processRenderUpdateForm(int $productId)
    {
        return View::make('crm.products.edit')->with(['product' => $this->productsService->loadProduct($productId)]);
    }

    public function processListOfProducts()
    {
        $productCount = ProductsModel::where('is_active', 1)->count();

        return View::make('crm.products.index')->with(
            [
                'productsPaginate' => $this->productsService->loadPagination(),
                'productCount' => $productCount
            ]
        );
    }


    public function processStoreProduct(ProductStoreRequest $request)
    {
        $validatedData = $request->validated();
    
        // Check if the barcode is provided
        $barcode = $validatedData['barcode'] ?? null;
        if (!$barcode) {
            // Generate a random 11-digit barcode
            $barcode = $this->generateRandomBarcode();
        }
    
        // Add the generated or provided barcode to the validated data
        $validatedData['barcode'] = $barcode;
    
        $product = VendorModel::find($validatedData['vendor_id']);
       
        $storedProductId = $this->productsService->execute($validatedData, $this->getAdminId());
       
            // Use the customLog helper function to log and insert data
            $message = 'Product has been added ';
            $logData = ['data' => $validatedData];
            $userId = Auth::id();
            $ipAddress = $request->ip();
            customLog($message, $logData, $userId, $ipAddress); 
            //end customLog helper function

        if ($storedProductId) {
            $message = 'Product has been added with ID ' . $storedProductId . ' - ' . json_encode($validatedData);
            $this->systemLogsService->loadInsertSystemLogs($message, $this->systemLogsService::successCode, $this->getAdminId());
                
            // Delete the authenticated user from the live_agent table
            $username = Auth::user()->name;
            DB::table('live_agent')->where('user_name', $username)->delete();
    
            return redirect('products')->with('message_success', $this->getMessage('messages.SuccessProductsStore'));
        } else {
            return back()->with('message_success', $this->getMessage('messages.ErrorProductsStore'));
        }
    }
    


    // Method to generate a random 11-digit barcode
    private function generateRandomBarcode()
    {
        return strval(rand(10000000000, 99999999999));
    }

    public function processUpdateProduct(ProductUpdateRequest $request, int $productId)
    {
        if ($this->productsService->update($productId, $request->validated())) {
            return Redirect::to('products')->with('message_success', $this->getMessage('messages.SuccessProductsStore'));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorProductsStore'));
        }
    }

    public function processDeleteProduct(int $productId)
    {
        // $clientAssigned = $this->productsService->checkIfProductHaveAssignedSale($productId);

        // if (!empty($clientAssigned)) {
        //     return Redirect::back()->with('message_danger', $clientAssigned);
        // } else {
        //     $productsDetails = $this->productsService->loadProduct($productId);
        //     $productsDetails->delete();
        // }

        // $this->systemLogsService->loadInsertSystemLogs('ProductsModel has been deleted with id: ' . $productsDetails->id, $this->systemLogsService::successCode, $this->getAdminId());

        // return Redirect::to('products')->with('message_success', $this->getMessage('messages.SuccessProductsDelete'));
      
        $product = ProductsModel::find($productId);

        if (!$product) {
            return Redirect::back()->with('message_danger', 'Product not found.');
        }
    
        $product->delete();
    
        $this->systemLogsService->loadInsertSystemLogs('Product has been deleted with id: ' . $productId, $this->systemLogsService::successCode, $this->getAdminId());
    
        return Redirect::to('products')->with('message_success', 'Product deleted successfully.');
    
    }

    public function processProductSetIsActive(int $productId, bool $value)
    {
        if ($this->productsService->loadIsActiveFunction($productId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('ProductsModel has been enabled with id: ' . $productId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessProductsActive' : 'ProductsIsNowDeactivated';

            return Redirect::to('products')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ProductsIsActived'));
        }
    }
}
