@extends('layouts.base')

@section('caption', 'Add purchase')

@section('title', 'Add purchase')

@section('lyric', 'Vert-Aage')

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
                            {{ Form::open(['route' => 'processStorePurchase']) }}
                            <div class="form-group input-row">
                                {{ Form::label('name', 'Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('product_id', 'Assign product') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    <select name="product_id" class="form-control" id="product_id">
                                        <option value="" disabled selected>Select product</option>
                                        @foreach ($dataOfProducts as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
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
                            <div class="form-group">
                                {{ Form::label('date_of_payment', 'Date of payment') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('date_of_payment', \Carbon\Carbon::now(), ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('price', 'Price') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                    {{ Form::text('price', null, ['class' => 'form-control', 'id' => 'price', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('gst_rate', 'GST Rate (%)') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    {{ Form::text('gst_rate', null, ['class' => 'form-control', 'placeholder' => 'GST Rate']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('total_price', 'Total Price') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                                    {{ Form::text('total_price', null, ['class' => 'form-control', 'readonly']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        </div>
                        <div class="col-lg-12 validate_form">
                            {{ Form::submit('Add Purchase', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
      $(document).ready(function () {
        function calculateTotalPrice() {
                var price = parseFloat($('#price').val());
                var quantity = parseInt($('input[name="quantity"]').val());
                var gstRate = parseFloat($('input[name="gst_rate"]').val());

                if (!isNaN(price) && !isNaN(quantity) && !isNaN(gstRate)) {
                    var totalPrice = (price * quantity) * (1 + (gstRate / 100));
                    $('input[name="total_price"]').val(totalPrice.toFixed(2));
                }
            }

            $('select[name="product_id"]').on('change', function () {
                var selectedProduct = $(this).val();
                var selectedPrice = $('option:selected', this).data('price');

                if (selectedProduct) {
                    $('#price').val(selectedPrice);
                    calculateTotalPrice();
                }
            });

            $('input[name="quantity"], input[name="gst_rate"]').on('change', function () {
                calculateTotalPrice();
            });

        function calculateTotalPrice() {
            var price = parseFloat($('#price').val());
            var quantity = parseInt($('input[name="quantity"]').val());

            if (!isNaN(price) && !isNaN(quantity)) {
                var totalPrice = price * quantity;
                $('input[name="total_price"]').val(totalPrice.toFixed(2));
            }
        }

        $('select[name="product_id"]').on('change', function () {
            var selectedProduct = $(this).val();
            var selectedPrice = $('option:selected', this).data('price');

            if (selectedProduct) {
                $('#price').val(selectedPrice);
                calculateTotalPrice();
            }
        });

        $('input[name="quantity"]').on('change', function () {
            calculateTotalPrice();
        });

    
            var validator = new formValidator({
                addError: function (field, message) {
                    var error_message_field = $('.error_message', field.parent('.input-group'));
    
                    if (!error_message_field.length) {
                        error_message_field = $('<span/>').addClass('error_message');
                        field.parent('.input-group').append(error_message_field);
                    }
    
                    error_message_field.text(message).show(200);
                    field.addClass('error');
                },
                removeError: function (field) {
                    $('.error_message', field.parent('.input-row')).text('').hide();
                    field.removeClass('error');
                },
                onErrors: function (errors, event) {
                    // Handle errors here
                },
                rules: {
                    'name': {
                        'field': $('input[name=name]'),
                        'validate': function (field, event) {
                            if (!field.val()) {
                                throw "A name is required.";
                            }
                        }
                    },
                    'quantity': {
                        'field': $('input[name=quantity]'),
                        'validate': function (field, event) {
                            if (!field.val()) {
                                throw "A quantity is required.";
                            }
                        }
                    },
                    'date_of_payment': {
                        'field': $('input[name=date_of_payment]'),
                        'validate': function (field, event) {
                            if (!field.val()) {
                                throw "A date of payment is required.";
                            }
                        }
                    },
                    'product_id': {
                        'field': $('select[name=product_id]'),
                        'validate': function (field, event) {
                            if (!field.val()) {
                                throw "Please select a product.";
                            }
                        }
                    }
                }
            });
    
            $('form').on('blur', 'input,select', function () {
                validator.validateField($(this).attr('name'), 'blur');
            });
    
            $('form').on('focus', 'input,select', function () {
                validator.clearError($(this).attr('name'));
            });
    
            $('.validate_section').click(function () {
                var fields = [];
                $('input,select', $(this).closest('.section')).each(function () {
                    fields.push($(this).attr('name'));
                });
    
                if (validator.validateFields(fields, 'submit')) {
                    alert('success');
                }
                return false;
            });
    
            $('.validate_form').click(function () {
                if (!validator.validateFields('submit')) {
                    return false;
                }
                return true;
            });
    
            $('.clear_section').click(function () {
                var fields = [];
                $('input,select', $(this).closest('.section')).each(function () {
                    fields.push($(this).attr('name'));
                });
    
                validator.clearErrors(fields);
                return false;
            });
        });
    </script>
    
    @endsection