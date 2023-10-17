@extends('layouts.base')

@section('caption', 'Information about sales')

@section('title', 'Invoice')

@section('content')

<style>
    /* Your custom styles */
    /* Add your table specific styles here */
    table,
    td,
    th {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }

    table {
        width: 100%;
        margin-bottom: 20px;
    }

    th {
        background-color: lightblue;
        text-align: left;
    }

    .invoice-title {
        text-align: center;
        font-size: 24px;
        margin-bottom: 30px;
    }

    .invoice-summary {
        float: right;
    }

    .invoice-summary td {
        font-weight: bold;
    }

    .from-section {
        float: left;
        width: 50%;
    }

    .to-section {
        float: right;
        width: 50%;
    }

    /* Clear the floats to prevent layout issues */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<div class="row">
    <div class="col-md-12 col-sm-6">
        @if(session()->has('message_success'))
            <div class="alert alert-success">
                <strong>Well done!</strong> {{ session()->get('message_success') }}
            </div>
        @elseif(session()->has('message_danger'))
            <div class="alert alert-danger">
                <strong>Danger!</strong> {{ session()->get('message_danger') }}
            </div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Sales Details</h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab">Basic information</a></li>
                    <div class="text-right">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                            Delete this sale <li class="fa fa-trash-o"></li>
                        </button>
                    </div>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade active in" id="home">
                        <br>
                        <div class="invoice">
                            <h3>
                                <button class="btn btn-primary" onclick="printTable()">Print</button>
                            </h3>
                        </div>
                        <br>
                        <table>
                            <tr>
                                <td class="from-section">
                                    <strong>From:</strong><br>
                                    Xenotabyte service pvt.ltd<br>
                                    <Strong>07982748233</Strong><br>
                                    B- 86 Sector-60<br>
                                    Noida, Uttar Pradesh<br>
                                    India<br>
                                    201301
                                </td>
                                <td class="to-section">
                                    <strong>To:</strong><br>
                                    @if ($sale->custmorData)
                                        {{ $sale->custmorData->full_name }}<br>
                                        {{ $sale->custmorData->phone }}<br>
                                        @if ($sale->custmorData->location)
                                            {{ $sale->custmorData->location }}<br>
                                        @endif
                                        {{ $sale->custmorData->city }}<br>
                                        {{ $sale->custmorData->country }}<br>
                                        {{ $sale->custmorData->zip }}<br>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <table class="invoice-table">
                          
                            <thead>
                                <tr>
                                    {{-- <th style="width: 30%;">Invoice No.</th> --}}
                                    <th style="width: 30%;">Coustmer Name</th>
                                    <th style="width: 30%;">Product Name</th>
                                    <th style="width: 15%;">Product Brand Name</th>
                                    <th style="width: 10%;">Product Category Name</th>
                                    <th style="width: 10%;">Product Sn</th>
                                    <th style="width: 15%;">Product Base Price</th>
                                    <th style="width: 15%;">GST Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- <td>{{ $sale->invoice_number ?? 'N/A' }}</td> --}}
                                    <td class="">
                                           {{ $sale->name }}
                                     </td>

                                    <td>
                                        @if ($sale->products)
                                            <a href="{{ URL::to('products/view/' . $sale->products->id) }}">{{ $sale->products->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{$sale->brand_name}}</td>
                                    <td>{{$sale->category->cat_name}}</td>
                                    <td>{{ $sale->sn ?? 'N/A' }}</td>
                                    <td>₹ {{ $sale->price ?? 'N/A' }}</td>
                                    <td>{{ $sale->gst_rate}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="invoice-summary">
                            <tbody>
                                <tr>
                                    <td style="width: 85%; text-align: left;"><strong>Product Sale Price:</strong></td>
                                    <td style="width: 15%;">₹ {{ $sale->sale_price}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 85%; text-align: left;"><strong>Gst Amount:</strong></td>
                                    <td style="width: 15%;">₹ {{$sale->gst_amount}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 85%; text-align: left;"><strong>Grand Total (Incl. GST):</strong></td>
                                    <td style="width: 15%;">₹ {{$sale->total_amount}}</td>
                                </tr>
                               
                                {{-- <tr>
                                    <td style="width: 85%; text-align: left;"><strong>Status:</strong></td>
                                    <td style="width: 15%;">{{ isset($sale->is_active) ? ($sale->is_active ? 'Active' : 'Deactivate') : 'N/A' }}</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>  
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">You want to delete this product?</h4>
                        </div>
                        <div class="modal-body">
                            Action will delete this product permanently.
                        </div>
                        <div class="modal-footer">
                            {{ Form::open(['url' => 'sales/delete/' . $sale->id, 'class' => 'pull-right']) }}
                            {{ Form::hidden('_method', 'DELETE') }}
                            {{ Form::submit('Delete this Sale', ['class' => 'btn btn-small btn-danger']) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>      
                    {{-- <div class="text-center">
                        <button onclick="printAndDownloadInvoice()">Print and Download Invoice</button>
                    </div> --}}

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
                    <script>
                        function printTable() {
                            // Get the table and summary content and create a new window
                            let tableContent = document.getElementsByClassName('invoice-table')[0].outerHTML;
                            let summaryContent = document.getElementsByClassName('invoice-summary')[0].outerHTML;
                            let printWindow = window.open('', '_blank');
                            printWindow.document.write('<html><head><title>Invoice</title>');
                            printWindow.document.write('<style>');
                            printWindow.document.write(`
                                /* Add your custom styles for the invoice here */

                                /* ... Rest of the CSS styles ... */

                                /* Add your table specific styles here */
                                table,
                                td,
                                th {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                    padding: 10px;
                                }

                                table {
                                    width: 100%;
                                    margin-bottom: 20px;
                                }

                                th {
                                    background-color: lightblue;
                                    text-align: left;
                                }
                                
                            `);
                            printWindow.document.write('</style>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write('<h1 class="invoice-title">Sales Invoice</h1>');
                            printWindow.document.write(tableContent);
                            printWindow.document.write('<hr>');
                            printWindow.document.write(summaryContent);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();

                            // Wait for the window content to load before printing
                            printWindow.onload = function () {
                                // Open the print dialog for the table content
                                printWindow.print();
                                printWindow.close();
                            };
                        }

                        function printAndDownloadInvoice() {
                            let printWindow = window.open('', '_blank');
                            printWindow.document.write('<html><head><title>Invoice</title>');

                            printWindow.document.write('<style>');
                            printWindow.document.write(`
                                table,
                                td,
                                th {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                    padding: 10px;
                                }

                                table {
                                    width: 100%;
                                    margin-bottom: 20px;
                                }

                                th {
                                    background-color: lightblue;
                                    text-align: left;
                                }

                                .invoice-title {
                                    text-align: center;
                                    font-size: 24px;
                                    margin-bottom: 30px;
                                }

                                .invoice-summary {
                                    float: right;
                                }

                                .invoice-summary td {
                                    font-weight: bold;
                                }
                            `);
                            printWindow.document.write('</style>');
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(document.getElementsByClassName('invoice')[0].outerHTML);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();

                            printWindow.onload = function () {
                                // Use jsPDF to generate the PDF
                                let pdf = new jsPDF();
                                pdf.fromHTML(printWindow.document.body, 15, 15, {
                                    width: 180
                                }, function () {
                                    // Trigger download of the PDF file
                                    pdf.save('invoice.pdf');

                                    // Close the print window
                                    printWindow.close();
                                });
                            };
                        }
                    </script>
                    @endsection
