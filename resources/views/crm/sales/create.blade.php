@extends('layouts.base')

@section('caption', 'Add Sales Products')

@section('title', 'Add Sales')

@section('lyric', '')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Danger!</strong> {!! implode('', $errors->all('<div>:message</div>')) !!}
        </div>
    @endif

    @if (session()->has('message_success'))
        <div class="alert alert-success">
            <strong>Well done!</strong> {{ session()->get('message_success') }}
        </div>
    @elseif(session()->has('message_danger'))
        <div class="alert alert-danger">
            <strong>Danger!</strong> {{ session()->get('message_danger') }}
        </div>
    @endif
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
        <li><a data-toggle="tab" href="#menu1">Barcode</a></li>
    </ul>
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                @if (!empty($dataOfCustomer))
                                    <div class="form-group col-lg-6">
                                        {{ Form::label('client_id', 'Choose Company') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                            {{ Form::select('client_id', $dataOfCustomer, null, ['class' => 'form-control', 'placeholder' => 'Choose Customer Company']) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-6">
                                    {{ Form::open(['route' => 'processStoreSale']) }}
                                    <div class="form-group input-row">
                                        {{ Form::label('name', 'Customer Name') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('cat_id', 'Select Product Category') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                            <select name="cat_id" class="form-control" id="product_category"
                                                onchange="getProducts();">
                                                <option value="" disabled selected>Select Product Category</option>
                                                @foreach ($category_name as $value)
                                                    <option value="{{ $value->id }}">{{ $value->cat_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('product_id', 'Product Name') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                            <select name="product_id" id="product_name" class="form-control"
                                                onchange="getProductsData();">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('sn', 'Product Serial Number') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                            {{ Form::text('sn', null, ['class' => 'form-control', 'readonly', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'id' => 'sn']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('brand_name', 'Product Brand') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                            {{ Form::text('brand_name', null, ['class' => 'form-control', 'readonly', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'id' => 'brand']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('price', 'Product Base Price') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            {{ Form::number('price', null, ['class' => 'form-control', 'readonly', 'id' => 'price', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('sale_price', 'Product Sale Price') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            {{ Form::number('sale_price', null, ['class' => 'form-control', 'id' => 'sale_price', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('gst_rate', 'GST Rate') }}
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            {{ Form::select('gst_rate', ['igst' => 'IGST 18%', 'sgst' => 'SGST 9%', 'cgst' => 'CGST 9%', 'sgst_cgst' => 'SGST + CGST 9%'], null, ['class' => 'form-control', 'placeholder' => 'Select GST Rate', 'id' => 'gst_rate']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('total_amount', 'Total Amount') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            {{ Form::text('total_amount', null, ['class' => 'form-control', 'id' => 'total_amount', 'readonly' => 'readonly']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('date_of_payment', 'Date of Sale') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            {{ Form::date('date_of_payment', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 validate_form">
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         {{-- Barcode tab --}}
        <div id="menu1" class="tab-pane fade">
            <div class="tab-content"><br>
                <div id="home" class="tab-pane fade in active">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        <div class="input-group">
                                            {{ Form::text('barcode', null, ['class' => 'form-control', 'id' => 'barcode', 'placeholder' => 'Search barcode']) }}
                                            <span class="input-group-btn">
                                                {{ Form::submit('Search', ['class' => 'btn btn-primary', 'id' => 'getProductsBarcode', 'aria-label' => 'Save Button']) }}
                                            </span>

                                        </div>
                                        <div class="alert alert-danger alert-dismissible" id="errormsg">
                                            <a href="#" class="close" data-dismiss="alert"
                                                aria-label="close">&times;</a>
                                            <strong>Error!</strong> No product found with the given barcode.
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            {{ Form::open(['route' => 'storeDataBehafOfBarcode','method'=>'post']) }}                            
                             @csrf
                                <div class="row hideElement">
                                    <div class="col-lg-6">
                                        <div class="form-group input-row">
                                            <label for="product_category">Product Category</label>
                                            <input  type="text" id="product_categoryBarcodeName" class="form-control" readonly>
                                            <input type="hidden" name="cat_id" id="product_categoryBarcodeId">
                                         </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group input-row">
                                            {{ Form::label('name', 'Customer Name') }}
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <div class="row hideElement">
                                <div class="col-lg-6">
                                    <div class="form-group input-row ">
                                        <label for="product_nameBarcode">Product Name</label>
                                        <input  name="product_name" id="product_nameBarcodeName" class="form-control" readonly>

                                        <input type="hidden" name="product_id" id="product_nameBarcodeId">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('client_id', 'Choose Company') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                            {{ Form::select('client_id', $dataOfCustomer, null, ['class' => 'form-control', 'placeholder' => 'Choose Customer Company']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row hideElement">
                                <div class="col-lg-6">
                                    <div class="form-group input-row ">
                                        <label for="sn">Product Serial Number</label>
                                        <input type="text" name ="sn" id="snBarcode" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <div class="form-group input-row">
                                        {{ Form::label('sale_price', 'Product Sale Price') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            {{ Form::number('sale_price', null, ['class' => 'form-control', 'id' => 'sale_priceBarcode', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hideElement">
                                <div class="col-lg-6">
                                    <div class="form-group input-row ">
                                        <label for="priceBarcode">Product Base Price</label>
                                        <input type="text" name="price" id="priceBarcode" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('gst_rate', 'GST Rate') }}
                                        <div class="input-group">
                                            <span class="input-group-addon">%</span>
                                            {{ Form::select('gst_rate', ['igst' => 'IGST 18%', 'sgst' => 'SGST 9%', 'cgst' => 'CGST 9%', 'sgst_cgst' => 'SGST + CGST 9%'], null, ['class' => 'form-control', 'placeholder' => 'Select GST Rate', 'id' => 'gst_rateBarcode']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row hideElement">
                                <div class="col-lg-6">
                                    <div class="form-group input-row ">
                                        <label for="brand_name">Product Brand Name</label>
                                        <input type="text" id="brand_nameBarcode" name="brand_name"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group input-row">
                                        {{ Form::label('total_amount', 'Total Amount') }}
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            {{ Form::text('total_amount', null, ['class' => 'form-control', 'id' => 'total_amountBarcode', 'readonly' => 'readonly']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hideElement">
                                <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('date_of_payment', 'Date of Sale') }}
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                {{ Form::date('date_of_payment', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                            </div>
                                        </div>
                                    </div>
                            </div>  
                                <div class="text-center hideElement">
                                    <button type="submit"  class="btn btn-primary">Submit</button>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    
    <script>

    // Function to update the total amount
    function updateTotalAmount() {
        var salePrice = parseFloat(document.getElementById('sale_priceBarcode').value);
        var gstRate = document.getElementById('gst_rateBarcode').value;
        var gstPercentage = 0;
            if (gstRate === 'igst')
             gstPercentage = 18;
            else if (gstRate === 'sgst') {
                gstPercentage = 9;
            }else if (gstRate === 'cgst') {
                gstPercentage = 9;
            }else if (gstRate === 'sgst_cgst') {
                gstPercentage = 9*2;
            }else {
                // Handle other cases if needed
            }


        var gstAmount = (salePrice * gstPercentage) / 100;
        var totalAmount = salePrice + gstAmount;

        // Round the totalAmount to two decimal places
        totalAmount = totalAmount.toFixed(2);

        document.getElementById('total_amountBarcode').value = totalAmount;
    }

    // Event listeners for input and select changes
    document.getElementById('sale_priceBarcode').addEventListener('input', updateTotalAmount);
    document.getElementById('gst_rateBarcode').addEventListener('change', updateTotalAmount);

        // Function to retrieve product names based on selected category
        function getProducts() {
            const selectedCat = $('#product_category').val();
    
            // Send an AJAX request to the server to get product names
            $.ajax({
                url: '<?php echo route('getProductName'); ?>',
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    category_id: selectedCat,
                },
                success: function (response) {
                    const productSelect = $('#product_name');
    
                    productSelect.empty();
    
                    if (response.status === 'success') {
                        productSelect.append('<option>Select</option>');
    
                        $.each(response.getproducts, function (key, value) {
                            const option = $('<option>', {
                                value: value.id,
                                'data-serial': value.product_serial_no,
                                'data-price': value.price,
                                'data-brand': value.brand_name
                            }).text(value.name);
    
                            productSelect.append(option);
                        });
                    }
                }
            });
        }
    
        // Function to populate product data when a product is selected
        function getProductsData() {
            const selectedOption = $('#product_name option:selected');
            const serial = selectedOption.data('serial');
            const price = selectedOption.data('price');
            const brand = selectedOption.data('brand');
            
            $('#price').val(price);
            $('#sn').val(serial);
            $('#brand').val(brand);
    
            // Call the calculateTotalAmount function when selecting a product
            calculateTotalAmount();
        }
    
        // References to input elements
        const salePriceInput = $('#sale_price');
        const gstRateSelect = $('#gst_rate');
        const totalAmountInput = $('#total_amount');
    
        // Function to calculate the total amount
        function calculateTotalAmount() {
            const salePrice = parseFloat(salePriceInput.val()) || 0;
            const gstRate = gstRateSelect.val();
            let gstAmount = 0;
    
            if (gstRate === 'igst') {
                gstAmount = salePrice * 0.18; // IGST 18%
            } else if (gstRate === 'sgst') {
                gstAmount = salePrice * 0.09; // SGST 9%
            } else if (gstRate === 'cgst') {
                gstAmount = salePrice * 0.09; // CGST 9%
            } else if (gstRate === 'sgst_cgst') {
                gstAmount = (salePrice * 0.09) * 2; // SGST 9% + CGST 9%
            }
    
            const totalAmount = salePrice + gstAmount;
            totalAmountInput.val(totalAmount.toFixed(2));
        }
    
        // Hide error message and elements initially
        $('#errormsg').hide();
        $('.hideElement').hide();
    
        // When the document is ready
        $(document).ready(function () {
            // Event listener for the "Get Products by Barcode" button
            $("#getProductsBarcode").click(function () {
                const barcode = $('#barcode').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo route('barcode'); ?>',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        barcode: barcode
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.status === 'success') {
                            // Populate product information and show the hidden elements
                            $('#barcode').val('');
                            $('#product_nameBarcodeName').val(response.products.name)
                            $('#product_categoryBarcodeName').val(response.products.cat_name);
                            $('#product_nameBarcodeId').val(response.products.id);
                             $('#product_categoryBarcodeId').val(response.products.product_category_id);
                            $('#snBarcode').val(response.products.product_serial_no);
                            $('#priceBarcode').val(response.products.price);
                            $('#brand_nameBarcode').val(response.products.brand_name);
                            $('.hideElement').show();
                        } else if (response.status === 'error') {
                            // Show an error message and reset the fields
                            $('#errormsg').show();
                            $('#barcode').val('');
                            $('#product_categoryBarcode').val('');
                            $('#product_nameBarcode').val('');
                            $('#snBarcode').val('');
                            $('#priceBarcode').val('');
                            $('#brand_nameBarcode').val('');
                        }
                    }
                });
            });
    
            // Event listeners for input and select fields to trigger the total amount calculation
            salePriceInput.on('input', calculateTotalAmount);
            gstRateSelect.change(calculateTotalAmount);
    
            // Call the initial calculation
            calculateTotalAmount();

            $(document).ready(function() {
                $('#submitForm').click(function() {
                    // Serialize the form data
                    var formData = $('#myForm').serialize();

                    // Make an AJAX POST request to the specified route
                    $.ajax({
                        url: "{{ url('storeDataBehafOfBarcode') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            formData : formData},
                        success: function(response) {
                            // Handle the success response, e.g., show a success message
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            // Handle errors, e.g., show an error message
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        });
    </script>
    

@endsection
