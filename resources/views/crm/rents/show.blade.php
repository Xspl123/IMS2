@extends('layouts.base')

@section('caption', 'Information about rents')

@section('title', 'Invoice')

@section('content')

<style>
    /* Add your custom styles for the invoice here */
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
        <br/>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Rent Details</h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab">Basic information</a></li>
                    <div class="text-right">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                            Delete this rent <i class="fa fa-trash-o"></i>
                        </button>
                        
                    </div>
                </ul>
                <div class="tab-pane fade active in" id="home">
                    <br>
                    <div class="invoice">
                        <h3>
                            <button class="btn btn-primary" onclick="printTable()">Print</button>
                        </h3>
                    </div>
                    <hr>
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                {{-- <th style="width: 30%;">Invoice No.</th> --}}
                                <th style="width: 30%;">Customer Name</th>
                                <th style="width: 30%;">Product Name</th>
                                <th style="width: 10%;">Qty.</th>
                                <th style="width: 15%;">Price</th>
                                <th style="width: 15%;">Total Amount</th>
                                <th style="width: 15%;">GST Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- <td>{{ $sale->invoice_number ?? 'N/A' }}</td> --}}
                                <td>{{ $rent->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($rent->products)
                                        <a href="{{ URL::to('products/view/' . $rent->products->id) }}">{{ $rent->products->name }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $rent->quantity ?? 'N/A' }}</td>
                                <td>₹ {{ $rent->price ?? 'N/A' }}</td>
                                <td>₹ {{ $rent->price * $rent->quantity }}</td>
                                <td>{{ $rent->gst_rate}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="invoice-summary">
                        <tbody>
                            <tr>
                                <td style="width: 85%; text-align: left;"><strong>Subtotal:</strong></td>
                                <td style="width: 15%;">₹ {{ $rent->price * $rent->quantity }}</td>
                            </tr>
                            <tr>
                                <td style="width: 85%; text-align: left;"><strong>Tax:</strong></td>
                                <td style="width: 15%;">₹ {{ $rent->price * $rent->quantity * (18 / 100) }}</td>
                            </tr>
                            <tr>
                                <td style="width: 85%; text-align: left;"><strong>Grand Total (Incl. GST):</strong></td>
                                @php
                                    // Calculate the GST amount based on the gst_rate field in the $rent object                                   
                                    $gstRate = $rent->gst_rate;
                                    $gstAmount = 0;
                            
                                    if ($gstRate === 'igst') {
                                        // Assuming IGST rate is 18%
                                        $gstAmount = $rent->price * $rent->quantity * 0.18;
                                    } elseif ($gstRate === 'sgst_cgst 18%') {
                                        // Assuming SGST and CGST rates are both 9%
                                        $gstAmount = ($rent->price * $rent->quantity * 0.09) * 2;
                                    } elseif ($gstRate === 'sgst 9%') {
                                        // Assuming SGST rate is 9%
                                        $gstAmount = $rent->price * $rent->quantity * 0.09;
                                    } elseif ($gstRate === 'cgst 9%') {
                                        // Assuming CGST rate is 9%
                                        $gstAmount = $rent->price * $rent->quantity * 0.09;
                                    }
                            
                                    // Calculate the total amount including GST
                                    $totalAmount = $rent->price * $rent->quantity + $gstAmount;
                                @endphp
                                <td style="width: 15%;">₹ {{ $totalAmount }}</td>
                            </tr>
                            <tr>
                                <td style="width: 85%; text-align: left;"><strong>Status:</strong></td>
                                <td style="width: 15%;">{{ isset($rent->is_active) ? ($rent->is_active ? 'Active' : 'Deactivate') : 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Are you sure you want to delete this rent?</h4>
            </div>
            <div class="modal-body">
                This action will permanently delete this rent.
            </div>
            <div class="modal-footer">
                <form action="{{ url('rents/delete/' . $rent->id) }}" method="POST" class="pull-right">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete this rent product</button>
                </form>
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
                            printWindow.document.write('<h1 class="invoice-title">rents Invoice</h1>');
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
