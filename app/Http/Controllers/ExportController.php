<?php
namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }


    
}
