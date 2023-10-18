@extends('layouts.base')

@section('caption', 'Edit products')

@section('title', 'Edit products')

@section('lyric', 'Vert-Age')

@section('content')
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
                            {{ Form::model($product, ['route' => ['processUpdateProduct', $product->id], 'method' => 'PUT']) }}
                            <div class="form-group input-row">
                                {{ Form::label('barcode', 'Barcode') }}
                                {{ Form::text('barcode', null, ['class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                            </div>

                            <div class="form-group input-row">
                                {{ Form::label('name', 'Product Name') }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                            </div>

                            <div class="form-group input-row">
                                {{ Form::label('description', 'Product Description') }}
                                {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('vendor_id', 'Assign Vendor') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::select('vendor_id', $dataOfVendors, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select a Vendor Name']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('price_with_gst', 'Product Price (Incl. GST):  ') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        {{ Form::text('price_with_gst', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'price_with_gst', 'readonly', 'id' => 'price_with_gst']) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('product_category_id', 'Product Category') }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                {{ Form::select('product_category_id', $product_cat, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select a Category Name']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('product_serial_no', 'Product Serial Number') }}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {{ Form::text('product_serial_no', null, ['class' => 'form-control', 'placeholder' => 'Enter product serial number', 'id' => 'barcode']) }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('price', 'Product Base Price') }}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {{ Form::number('price', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'price']) }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('gstAmount', 'GST Amount') }}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {{ Form::number('gstAmount', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'gstAmount', 'readonly', 'id' => 'gstAmount']) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 validate_form">
                        {{ Form::submit('Edit product', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Create a formValidator object
        var validator = new formValidator({
            addError: function (field, message) {
                var error_message_field = $('.error_message', field.parent('.input-row'));
                if (!error_message_field.length) {
                    error_message_field = $('<span/>').addClass('error_message');
                    field.parent('.input-row').append(error_message_field);
                }
                error_message_field.text(message).show(200);
                field.addClass('error');
            },
            removeError: function (field) {
                $('.error_message', field.parent('.input-row')).text('').hide();
                field.removeClass('error');
            },
            onErrors: function (errors, event) {
                // Handle errors, if needed
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
                // Add more validation rules here if needed
            }
        });

        $('form').on('blur', 'input,select', function () {
            validator.validateField($(this).attr('name'), 'blur');
        });

        $('form').on('focus', 'input,select', function () {
            validator.clearError($(this).attr('name'));
        });

        $('.validate_form').click(function () {
            if (!validator.validateFields('submit')) {
                return false;
            }
            return true;
        });
    });
</script>
@endsection
