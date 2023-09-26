@extends('layouts.base')

@section('caption', 'Add Rental Products')

@section('title', 'Add Rental')

@section('lyric', '')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Danger!</strong> {!! implode('', $errors->all('<div>:message</div>')) !!}
        </div>
    @endif

    @if(session()->has('message_success'))
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
                            {{ Form::open(['route' => 'processStoreRent']) }}
                            <div class="form-group input-row">
                                {{ Form::label('name', 'Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            {{ Form::label('client_id', 'Select Coustomer') }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                {{ Form::select('client_id', $dataOfClients, null, ['class' => 'form-control', 'placeholder' => 'Select Coustomer']) }}
                            </div>
                        </div>
                        <div class="col-lg-6">
                           
                            <div class="form-group input-row">
                                {{ Form::label('product_id', 'Select Brand') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    <select name="product_id" class="form-control" id="product_id">
                                        <option value="" disabled selected>Select Brand</option>
                                        @foreach ($dataOfProducts as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">{{ $product->brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('product_name', 'Product') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                    <input type="text" name="product_name" id="product_name" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('quantity', 'Quantity') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                    {{ Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('gst_rate', 'GST Rate') }}
                                <div class="input-group">
                                    <span class="input-group-addon"> %</span>
                                    {{ Form::select('gst_rate', ['igst' => 'IGST 18%', 'sgst' => 'SGST 9%', 'cgst' => 'CGST 9%', 'sgst_cgst' => 'SGST + CGST 9%'], null, ['class' => 'form-control', 'placeholder' => 'Select GST Rate']) }}
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('date_of_payment', 'Date of payment') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('date_of_payment', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('rent_start', 'Rent Start Date') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('rent_start', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('rent_end', 'Rent End Date') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('rent_end', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                <div class="form-group input-row">
                                    {{ Form::label('price', 'Price') }}
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        {{ Form::text('price', null, ['class' => 'form-control', 'id' => 'price', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('total_amount', 'Total Amount') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                                    {{ Form::text('total_amount', null, ['class' => 'form-control', 'readonly']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                        </div>
                        <div class="col-lg-12 validate_form">
                            {{ Form::submit('Add Rent', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Get references to the select and input elements
        const selectBrand = document.getElementById('product_id');
        const productNameInput = document.getElementById('product_name');
        const productPriceInput = document.getElementById('price');

        // Call the function to set the initial total amount value
        calculateTotalPrice();

        // Add event listeners to the required fields
        $('input[name="quantity"], select[name="gst_rate"], #price').on('change', function () {
            calculateTotalPrice();
        });

        // Add event listener to the select element
        selectBrand.addEventListener('change', (event) => {
            const selectedOption = event.target.options[event.target.selectedIndex];

            // Get the data attributes from the selected option
            const productName = selectedOption.getAttribute('data-name');
            const productPrice = selectedOption.getAttribute('data-price');

            // Update the input fields with the selected product and its price
            productNameInput.value = productName;
            productPriceInput.value = productPrice;

            // Update the total price
            calculateTotalPrice();
        });

       function calculateTotalPrice() {
        var price = parseFloat($('#price').val());
        var quantity = parseInt($('input[name="quantity"]').val());
        var gstRate = $('select[name="gst_rate"]').val();

        if (!isNaN(price) && !isNaN(quantity)) {
            var gstAmount = 0;

            if (gstRate === 'igst') {
                gstAmount = price * quantity * 0.18; // IGST 18%
            } else if (gstRate === 'sgst') {
                gstAmount = price * quantity * 0.09; // SGST 9%
            } else if (gstRate === 'cgst') {
                gstAmount = price * quantity * 0.09; // CGST 9%
            } else if (gstRate === 'sgst_cgst') {
                gstAmount = (price * quantity * 0.09) * 2; // SGST 9% + CGST 9%
            }

            var totalPrice = price * quantity + gstAmount;
            $('input[name="total_amount"]').val(totalPrice.toFixed(2));
        }
    }

    </script>
@endsection
