@extends('layouts.base')

@section('caption', 'Edit Accounts')

@section('title', 'Edit Accounts')

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
                            {{ Form::model($account, ['route' => ['processUpdateAccount', $account->id], 'method' => 'PUT']) }}
                            <div class="form-group input-row">
                                {{ Form::label('account', 'Account Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                                {{ Form::text('account', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('account_number', 'Account Number') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('account_number', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('bank_name', 'Bank Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('balance', 'Initial Balance') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('balance', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('contact_person', 'Contact Person') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('contact_person', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('contact_phone', 'Contact Number') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('contact_phone', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('description', 'Description') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12 validate_form">
                            {{ Form::submit('Edit account', ['class' => 'btn btn-primary']) }}
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
                // rules: {
                //     //this is a basic non-empty check
                //     'name': {
                //         'field': $('input[name=name]'),
                //         'validate': function (field, event) {
                //             if (!field.val()) {
                //                 throw "A name is required.";
                //             }
                //         }
                //     }
                // }
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
    </script>

@endsection