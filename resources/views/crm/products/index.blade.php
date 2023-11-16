@extends('layouts.base')

@section('caption', 'List of products')

@section('title', 'List of products')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <button class="btn btn-primary btn active">
                <a href="{{ route('export.products') }}" style="color: white;">Download Products List</a>&nbsp;

            </button> <button class="btn btn-primary btn active " onclick="printBarcodes()">Print Barcodes</button>


            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> List of Products
                </div>
                <div class="panel-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-striped table-bordered table-hover" id="dtBasicExample" data-sortable>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th style="display: none">Barcode</th>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Product Name</th>
                                    <th>Product Category</th>
                                    <th>Product Serial No.</th>
                                    <th>Product Brand Name</th>
                                    <th>Product Type</th>
                                    <th>Status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productCount as $product)
                                    <tr class="{{ $product->barcode ? 'barcode-available' : 'barcode-not-available' }}">
                                        <td>
                                            <input type="checkbox" class="barcode-checkbox">
                                        </td>
                                        <td>{{ $product->created_at->format('l, F d, Y H:i:s') }}</td>
                                        <td>
                                            @if ($product->vendor)
                                            <a href="{{ URL::to('vendors/view/' . $product->vendor->id) }}">{{ $product->vendor->name }}</a>
                                            @endif
                                        </td>
                                        <td style="display: none">
                                            @if ($product->barcode)
                                                @php
                                                    $barcodeValue = $product->barcode;
                                                    $barcodeOptions = ['text' => $barcodeValue];
                                                    $rendererOptions = ['imageType' => 'png'];
                                                    $barcode = Zend\Barcode\Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions);
                                                    $imageResource = $barcode->draw();
                                                    $storagePath = storage_path('barcodes/');
                                                    if (!file_exists($storagePath)) {
                                                        mkdir($storagePath, 0777, true);
                                                    }
                                                    $barcodeImagePath = $storagePath . $product->barcode . '-' . $barcodeValue . '.png';
                                                    imagepng($imageResource, $barcodeImagePath);
                                                @endphp
                                                <img src="{{ asset('storage/barcodes/' . $barcodeValue . '-' . $barcodeValue . '.png') }}"
                                                    alt="Barcode" style="width:200px; height:73px;">
                                            @else
                                                No Barcode Available
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->cat_name }}</td>
                                        <td>{{ $product->product_serial_no }}</td>
                                        <td>{{ $product->brand_name }}</td>
                                        <td>{{ $product->product_type }}</td>
                                        <td>
                                            {{ $product->is_active ? 'Available' : 'Sold Out' }}
                                        </td>
                                        <td style="width: 100px">
                                            <div class="btn-group" style="width: 100px">
                                                <a class="btn btn-small btn-primary "
                                                    href="{{ URL::to('products/view/' . $product->id) }}">Details</a>
                                                <button data-toggle="dropdown"
                                                    class="btn btn-primary dropdown-toggle  "><span
                                                        class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a
                                                            href="{{ URL::to('products/form/update/' . $product->id) }}">Edit</a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Some option</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dtBasicExample').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });

        function printBarcodes() {
            var table = document.getElementById("dtBasicExample");
            var rows = table.getElementsByTagName("tr");
            var printTable = document.createElement("div");
            printTable.style.width = '210mm'; // A4 width in millimeters
            printTable.style.height = '42mm'; // A4 height in millimeters
            printTable.style.margin = 'auto'; // Center the content on the page
            printTable.style.display = 'flex';
            printTable.style.flexWrap = 'wrap';

            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var checkbox = row.querySelector(".barcode-checkbox");

                if (checkbox && checkbox.checked) {
                    var barcodeCell = row.cells[row.cells.length - 8];
                    console.log(barcodeCell);
                    if (barcodeCell) {
                        var img_tag = barcodeCell.innerHTML;
                        if (img_tag.indexOf('img') != -1) {
                            var printRow = document.createElement("div");
                            printRow.style.marginRight = '5px';
                            printRow.style.marginBottom = '5px';
                            printRow.innerHTML = img_tag;
                            printTable.appendChild(printRow);
                        }
                    }
                }
            }

            var printWindow = window.open("", "_blank");
            printWindow.document.open();
            printWindow.document.write('<html><head><title>Barcodes</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printTable.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
        }

        document.getElementById('checkAll').addEventListener('change', function() {
            var checkboxes = document.getElementsByClassName('barcode-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                var row = checkboxes[i].closest('tr');
                if (row.classList.contains('barcode-available')) {
                    checkboxes[i].checked = this.checked;
                }
            }
        });
    </script>
@endsection
