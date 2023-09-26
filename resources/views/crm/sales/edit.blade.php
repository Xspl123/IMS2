@extends('layouts.base')

@section('caption', 'Edit sales')

@section('title', 'Edit sales')

@section('lyric', 'lorem ipsum')

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
                            {{ Form::model($sale, ['route' => ['processUpdateSale', $sale->id], 'method' => 'PUT']) }}
                            <div class="form-group input-row">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', null, ['class' => 'form-control' ,'readonly']) }}
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('product_id', 'Assign product') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::select('product_id', $dataWithPluckOfProducts, null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                <div class="form-group input-row">
                                    {{ Form::label('quantity', 'Quantity') }}
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                        {{ Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('date_of_payment', 'Date of payment') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('date_of_payment', null, ['class' => 'form-control', 'required', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('price', 'Price') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text('price', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('status', 'Status') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-flag"></i></span>
                                    {{ Form::select('status', [
                                        'ok' => 'Ok',
                                        'defected' => 'Defected',
                                        'replacement' => 'Replacement',
                                    ], null, ['class' => 'form-control', 'id' => 'statusSelect']) }}
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-lg-6" id="approved_by" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('approved_by', 'Approved By') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-exclamation-circle"></i></span>
                                    {{ Form::text('approved_by', $auth, ['class' => 'form-control' ,'readonly', 'placeholder' => 'Write product issues here']) }}
                                </div>
                            </div>
                        </div>


                        
                        <div class="col-lg-6" id="replacementWith" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('replacement_with', 'Replacement with') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-exclamation-circle"></i></span>
                                    {{ Form::select('replacement_with', $dataWithPluckOfProducts, null, ['class' => 'form-control', 'id' => 'defectedInput', 'placeholder' => 'Select a Product']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" id="replacement_product_sn" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('replacement_product_sn', 'Replacement Serial') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                    {{ Form::text('replacement_product_sn', null, ['class' => 'form-control', 'placeholder' => 'Write product issues here']) }}
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-lg-6" id="replacementTo" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('replacement_to', 'Replacement To') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-exclamation-circle"></i></span>
                                    {{ Form::select('replacement_to', $dataOfCustomer, null, ['class' => 'form-control', 'id' => 'defectedInput', 'placeholder' => 'Select a Customer']) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6" id="replacementRemarkInput" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('replace_remark', 'Replacement Remark') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                    {{ Form::text('replace_remark', null, ['class' => 'form-control','placeholder' => 'Write product issues hear']) }}
                                </div>
                            </div>
                        </div>
                        
                        <script>
                           
                        </script>
                        

                        <div class="col-lg-6">
                        </div>

                        <div class="col-lg-12 validate_form">
                            {{ Form::submit('Edit sales', ['class' => 'btn btn-primary']) }}
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            //create formValidator object
            //there are a lot of configuration options that need to be passed,
            //but this makes it extremely flexibility and doesn't make any assumptions
            var validator = new formValidator({
                //this function adds an error message to a form field
                addError: function (field, message) {
                    //get existing error message field
                    var error_message_field = $('.error_message', field.parent('.input-row'));

                    //if the error message field doesn't exist yet, add it
                    if (!error_message_field.length) {
                        error_message_field = $('<span/>').addClass('error_message');
                        field.parent('.input-row').append(error_message_field);
                    }

                    error_message_field.text(message).show(200);
                    field.addClass('error');
                },
                //this removes an error from a form field
                removeError: function (field) {
                    $('.error_message', field.parent('.input-row')).text('').hide();
                    field.removeClass('error');
                },
                //this is a final callback after failing to validate one or more fields
                //it can be used to display a summary message, scroll to the first error, etc.
                onErrors: function (errors, event) {
                    //errors is an array of objects, each containing a 'field' and 'message' parameter
                },
                //this defines the actual validation rules
                rules: {
                    //this is a basic non-empty check
                    'name': {
                        'field': $('input[name=name]'),
                        'validate': function (field, event) {
                            if (!field.val()) {
                                throw "A name is required.";
                            }
                        }
                    }
                }
            });

            //now, we attach events

            //this does validation every time a field loses focus
            $('form').on('blur', 'input,select', function () {
                validator.validateField($(this).attr('name'), 'blur');
            });

            //this clears errors every time a field gains focus
            $('form').on('focus', 'input,select', function () {
                validator.clearError($(this).attr('name'));
            });

            //this is for the validate links
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

            //this is for the clear links
            $('.clear_section').click(function () {
                var fields = [];
                $('input,select', $(this).closest('.section')).each(function () {
                    fields.push($(this).attr('name'));
                });

                validator.clearErrors(fields);
                return false;
            });
        });
 
        $('#statusSelect').change(function () {
            var selectedStatus = $(this).val();

            // Hide both input fields initially
            $('#replacementWith').hide();
            $('#replacementTo').hide();
            $('#replacementRemarkInput').hide();
            $('#replacement_product_sn').hide();
            $('#approved_by').hide();


            // Show/hide input fields based on selected option
            if (selectedStatus === 'replacement') {
                $('#replacementWith').show();
                $('#replacementTo').show();
                $('#replacementRemarkInput').show();
                $('#replacement_product_sn').show();
                $('#approved_by').show();
            }

            $('#productSelect').change(function () {
            var selectedProductId = $(this).val();

            // Fetch the product name based on the selected product ID (dataWithPluckOfProducts is assumed to be an object mapping product IDs to product names)
            var productName = dataWithPluckOfProducts[selectedProductId];

            // Update the input field with the product name
            $('#defectedInput').val(productName);
        });
    
          
});

        
    </script>
@endsection
