<?php

namespace App\Http\Controllers\CRM;

use App\Enums\SystemEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\RentStoreRequest;
use App\Http\Requests\RentUpdateRequest;
use App\Models\ProductsModel;
use App\Models\RentsModel;
use App\Models\ClientsModel;
use App\Models\SalesModel;
use App\Models\InvoiceModel;
use App\Services\ProductsService;
use App\Services\RentsService;
use App\Services\SystemLogService;
use App\Services\InvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


class RentsController extends Controller
{
    private $rentsService;
    private $invoicesService;
    private $systemLogsService;
    private $productsService;

    public function __construct(RentsService $rentsService, SystemLogService $systemLogService, ProductsService $productsService, InvoicesService $invoicesService)
    {
        $this->middleware(SystemEnums::middleWareAuth);

        $this->rentsService = $rentsService;
        $this->invoicesService = $invoicesService;
        $this->systemLogsService = $systemLogService;
        $this->productsService = $productsService;
    }

    public function processRenderCreateForm()
    {
        $dataOfProducts = $this->productsService->loadProducts();
        $dataOfClients = ClientsModel::pluck('full_name', 'id');

        return View::make('crm.rents.create')->with([
            'dataOfProducts' => $dataOfProducts,
            'dataOfClients' => $dataOfClients,
        ]);
    }

    public function processShowrentsDetails($rentId)
    {
        return View::make('crm.rents.show')->with(['rent' => $this->rentsService->loadRent($rentId)]);
    }

    public function processRenderUpdateForm(Request $request, $rentId)
    {
        $requestData = $request->all();

        try {
            $rent = $this->rentsService->find($rentId);

            return View::make('crm.rents.edit')->with([
                'rent' => $rent,
                'dataWithPluckOfProducts' => ProductsModel::pluck('name', 'id')
            ]);
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to load rent: ' . $e->getMessage());
        }
    }

    public function processListOfRents()
    {
        $dataOfClients = ClientsModel::pluck('full_name', 'id');
        return View::make('crm.rents.index')->with([
            'rents' => $this->rentsService->loadRents(),
            'rentsPaginate' => $this->rentsService->loadPaginate(),
            'dataOfClients' => $dataOfClients
        ]);
    }

    public function processStoreRent(RentStoreRequest $request)
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

        // Save the sale with the GST amount and total amount
        $storedRentId = $this->rentsService->execute($validatedData, $this->getAdminId());

        if ($storedRentId) {
            // Create and save the invoice data
            $invoiceData = [
                'rent_id' => $storedRentId,
                'invoice_number' => '#Xeno/Up23-24/' . $storedRentId, // generate a unique invoice number based on your requirements
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
                
                $this->systemLogsService->loadInsertSystemLogs('RentsModel has been added with id: ' . $storedRentId, $this->systemLogsService::successCode, $this->getAdminId());
                return Redirect::to('rents')->with('message_success', $this->getMessage('messages.SuccessRentsStore'));
            }
        }

        return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorRentsStore'));
    }
 

    public function showInvoice($id)
    {
        $invoice = InvoiceModel::where('rent_id',$id)->first();

        $rent_id = $invoice->rent_id;

        $rents = DB::table('rents')->select('quantity','gst_rate')->where('id',$rent_id )->get();

        // Pass the invoice and rent data to the view
        return View::make('crm.rents.invoice', compact('invoice','rents'));
    }

    public function processUpdateRent(RentUpdateRequest $request, int $rentId)
    {
        try {
            $rent = $this->rentsService->find($rentId);
            $requestData = $request->validated();

            $updatedRent = $this->rentsService->updaterent($rentId, $requestData);

            return Redirect::to('rents')->with('message_success', 'Rent updated successfully.');
        } catch (\Exception $e) {
            return Redirect::back()->with('message_danger', 'Failed to update Rent: ' . $e->getMessage());
        }
    }

    public function processDeleteRent(int $rentId)
    {
        $rentsDetails = $this->rentsService->loadRent($rentId);
        $rentsDetails->delete();

        return Redirect::to('rents')->with('message_success', 'Rented  deleted successfully.');
    }

    public function processRentSetIsActive(int $rentId, bool $value)
    {
        if ($this->rentsService->loadIsActive($rentId, $value)) {
            $this->systemLogsService->loadInsertSystemLogs('RentsModel has been enabled with id: ' . $rentId, $this->systemLogsService::successCode, $this->getAdminId());

            $msg = $value ? 'SuccessrentsActive' : 'RentsIsNowDeactivated';

            return Redirect::to('rents')->with('message_success', $this->getMessage('messages.' . $msg));
        } else {
            return Redirect::back()->with('message_danger', $this->getMessage('messages.ErrorrentsActive'));
        }
    }
}