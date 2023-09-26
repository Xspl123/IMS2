<?php

namespace App\Services;

use App\Models\InvoiceModel;

class InvoicesService
{
    public function execute(array $data, $adminId): ?int
    {
        // Here, $data is the array containing invoice data such as 'sale_id', 'invoice_number', 'invoice_date', etc.
        // $adminId is optional and can be used if you need to track the admin/user who created the invoice.

        // Save the invoice data in the database
        $invoice = InvoiceModel::create($data);

        if ($invoice) {
            // Return the ID of the newly created invoice
            return $invoice->id;
        }

        // Return null if there was an error saving the invoice
        return null;
    }
}
