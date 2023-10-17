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

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
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
                                    {{ Form::text('sn', null, ['class' => 'form-control', 'readonly','placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'id' => 'sn']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('brand_name', 'Product Brand') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                    {{ Form::text('brand_name', null, ['class' => 'form-control', 'readonly','placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'id' => 'brand']) }}
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
                    
                        
                        <script>
                            // Get references to the input elements
                            const salePriceInput = document.getElementById('sale_price');
                            const gstRateSelect = document.getElementById('gst_rate');
                            const totalAmountInput = document.getElementById('total_amount');
                        
                            // Call the function to set the initial total amount value
                            calculateTotalAmount();
                        
                            // Add event listeners to the required fields
                            salePriceInput.addEventListener('input', function () {
                                calculateTotalAmount();
                            });
                        
                            gstRateSelect.addEventListener('change', function () {
                                calculateTotalAmount();
                            });
                        
                            function calculateTotalAmount() {
                                var salePrice = parseFloat(salePriceInput.value) || 0;
                                var gstRate = gstRateSelect.value;
                        
                                var gstAmount = 0;
                        
                                if (gstRate === 'igst') {
                                    gstAmount = salePrice * 0.18; // IGST 18%
                                } else if (gstRate === 'sgst') {
                                    gstAmount = salePrice * 0.09; // SGST 9%
                                } else if (gstRate === 'cgst') {
                                    gstAmount = salePrice * 0.09; // CGST 9%
                                } else if (gstRate === 'sgst_cgst') {
                                    gstAmount = (salePrice * 0.09) * 2; // SGST 9% + CGST 9%
                                }

                                var totalAmount = salePrice + gstAmount;
                                totalAmountInput.value = totalAmount.toFixed(2);
                            }
                        </script>

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

    <script>
        function getProducts() {
            var selectedCat = $('#product_category').val();
            $.ajax({
                url: '<?php echo route('getProductName'); ?>',
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    category_id: selectedCat,
                },
                success: function(response) {
                    $('#product_name').empty();
                    if (response.status == 'success') {
                        $('#product_name').append('<option>Select</option>');
                        $.each(response.getproducts, function(key, value) {
                            console.log(value);
                                $('#product_name').append('<option value="' + value.id + '" data-serial="' +
                                    value.product_serial_no + '" data-price="' + value.price + '" data-brand="' + value.brand_name + '">' +
                                    value.name + '</option>');

                        });
                    }
                }
            });

        }

        function getProductsData() {
            var selectedOption = $('#product_name option:selected');
            var serial = selectedOption.data('serial');
            var price = selectedOption.data('price');
            var brand =  selectedOption.data('brand');
            $('#price').val(price);
            $('#sn').val(serial)
            $('#brand').val(brand);
        }
    </script>

    {{-- <script>
        // Get references to the select and input elements
        const selectBrand = document.getElementById('product_brand');
        const productNameInput = document.getElementById('product_name');
        const productPriceInput = document.getElementById('sale_price');
        const productSnInput = document.getElementById('sn');
        const gstRateSelect = document.getElementById('gst_rate');

        // Call the function to set the initial total amount value
        calculateTotalPrice();

        // Add event listeners to the required fields
        $('input[name="quantity"], select[name="gst_rate"], #sale_price').on('change', function () {
            calculateTotalsale_price();
        });

        // Add event listener to the select element
        selectBrand.addEventListener('change', (event) => {
            const selectedOption = event.target.options[event.target.selectedIndex];

            const selectedBrandName = selectedOption.value;
            $('#selectProduct').empty();
            for (const option of selectBrand.options) {
                if (option.value === selectedBrandName) {
                    // Get the data attributes from the selected option
                    const productName = option.getAttribute('data-name');
                  
                    // Create a new option for the product select box
                    const productOption = document.createElement('option');
                   
                    productOption.value = productName;
                    productOption.text = productName;

                    // Append the product option to the product select box
                    selectProduct.appendChild(productOption);
                }
            }

            // Get the data attributes from the selected option
            const productName = selectedOption.getAttribute('data-name');
            const productsale_price = selectedOption.getAttribute('data-sale_price');
            const productSn = selectedOption.getAttribute('data-sn');
            const gstRate = selectedOption.getAttribute('data-gst');

            // Update the input fields with the selected product and its sale_price
            productNameInput.value = productName;
            productsale_priceInput.value = productsale_price;
            productSnInput.value = productSn;

            // Update the GST rate select field
            gstRateSelect.value = gstRate;

            // Update the total sale_price
            calculateTotalsale_price();
        });

        function calculateTotalsale_price() {
            var sale_price = parseFloat($('#sale_price').val());
            var gstRate = $('#gst_rate').val();

            if (!isNaN(sale_price)) {
                var gstAmount = 0;

                if (gstRate === 'igst') {
                    gstAmount = sale_price * 0.18; // IGST 18%
                } else if (gstRate === 'sgst') {
                    gstAmount = sale_price * 0.09; // SGST 9%
                } else if (gstRate === 'cgst') {
                    gstAmount = sale_price * 0.09; // CGST 9%
                } else if (gstRate === 'sgst_cgst') {
                    gstAmount = (sale_price * 0.09) * 2; // SGST 9% + CGST 9%
                }

                var totalsale_price = sale_price + gstAmount;
                $('input[name="total_amount"]').val(totalsale_price.toFixed(2));
            }
        }
    </script> --}}
@endsection
