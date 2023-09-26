<!-- resources/views/invoice.blade.php -->

@extends('layouts.base')

@section('title', 'Invoice Details')

@section('content')
<style>
    /* Add your custom styles for the invoice here */

    .invoice {
        margin: 30px auto;
        max-width: 800px;
        padding: 20px;
        border: 1px solid #ccc;
        font-family: Arial, sans-serif;
    }

    .invoice h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table th,
    .invoice-table td {
        border: 1px solid #ccc;
        padding: 10px;
    }

    .invoice-table th {
        background-color: #f2f2f2;
    }

    .invoice-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .invoice-table tr:hover {
        background-color: #f2f2f2;
    }

    .invoice-table td[colspan="4"] {
        text-align: right;
    }

    .invoice-table td[colspan="2"] {
        text-align: right;
    }

    .invoice-table td[colspan="3"],
    .invoice-table td[colspan="2"] {
        font-weight: bold;
    }

    /* Add any additional styles or modifications as needed */
</style>

<div class="invoice">
    <h2>Invoice Details</h2>
    <hr>
    <table class="invoice-table">
        <tr>
            <td><strong>Invoice Number:</strong></td>
            <td>{{ $invoice->invoice_number }}</td>
            <td><strong>Invoice Date:</strong></td>
            <td>{{ $invoice->invoice_date }}</td>
        </tr>
        <tr>
            <td colspan="3" ><strong>Vendor Details:</strong></td>
            <td class="text-center">
                @if ($invoice->payeeData)
               <strong> {{ $invoice->payeeData->name }}</strong>
                @endif
            </td>
        </tr>
        @foreach ($sales as $sale)
        <tr>
            <td colspan="3">
                <strong>GST Rate</strong>
            </td>
           <td> {{ $sale->gst_rate }}</td>
        </tr>
        @endforeach
        <tr>
            <th>Name</th>
            <th>Qty.</th>
            <th>Base Price</th>
            <th>Price (inc Gst)</th>
        </tr>
        <tr>
            <td>{{ $invoice->customer_details }}</td>
        @foreach ($sales as $sale)
            
                <td>{{ $sale->quantity }}</td>
            
        @endforeach
            <td>{{ $invoice->subtotal }}</td>
            <td>{{ $invoice->grand_total }}</td>
        </tr>
        <tr>
            
            <td colspan=""><strong>Tax (GST):</strong></td>
            <td>{{ $invoice->tax }}</td>
            <td colspan=""><strong>Grand Total:</strong></td>
            <td>{{ $invoice->grand_total }}</td>
        </tr>
    </table>
    
    <br>
    <div class="text-center">
        <button class="btn btn-primary" onclick="window.print();">Print Invoice</button>
    </div>
</div>

@endsection
