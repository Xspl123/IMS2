@extends('layouts.base')

@section('caption', 'New Deposit')

@section('title', 'New Deposit')

@section('lyric', '')

@section('content')
    {{-- @if(count($dataWithPluckOfCompanies) == 0)
        <div class="alert alert-danger">
            <strong>Danger!</strong> There is no companies in system. Please create any client. <a
                    href="{{ URL::to('companies/create') }}">Click here!</a>
        </div>
    @endif --}}

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
                            {{ Form::open(['route' => 'processStoreIncome']) }}
                            <div class="form-group input-row ">
                                {{ Form::label('account', 'Choose Account') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::select('account', $dataOfAccounts, null, ['class' => 'form-control', 'placeholder' => 'Choose Account','required']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('date', 'Date') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') ,'required']) }}
                                </div>
                            </div>                            
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('description', 'Description') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') ,'required']) }}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('type', 'Type') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                    {{ Form::select('type', ['Income' => 'Income', 'Expense' => 'Expense', 'Transfer' => 'Transfer'], null, ['class' => 'form-control', 'placeholder' => 'Select Type']) }}
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('amount', 'Amount') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                    {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') ,'required']) }}
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('category', 'Category') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::select('category', $dataOfCategories, null, ['class' => 'form-control', 'placeholder' => 'Uncategorized']) }}
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('tags', 'Tags') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('tags', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') ,'required']) }}
                                </div>
                            </div>
                        </div>
                            
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="form-group input-row">
                                    {{ Form::label('payerid', 'Payer') }}
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                        {{ Form::select('payerid', $dataOfPayers, null, ['class' => 'form-control', 'placeholder' => 'Choose Customer']) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('method', 'Method') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::select('method', $dataOfPmethod, null, ['class' => 'form-control', 'placeholder' => 'Select Payment Method']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('ref', 'Ref') }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
                                    {{ Form::text('ref', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') ,'required']) }}
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
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

        <script>
            $(document).ready(function () {
                // Initialize form validation
                $('form').validate({
                    rules: {
                        // Define validation rules for your form fields
                        account: {
                            required: true
                        },
                        date: {
                            required: true,
                            date: true
                        },
                        description: {
                            required: true
                        },
                        // Add rules for other fields
                    },
                    messages: {
                        // Define custom error messages for your form fields
                        account: {
                            required: "The account field is required."
                        },
                        date: {
                            required: "The date field is required.",
                            date: "The date must be a valid date format."
                        },
                        description: {
                            required: "The description field is required."
                        },
                        // Add custom error messages for other fields
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        // Display error messages next to the form fields
                        error.addClass('text-danger');
                        error.insertAfter(element);
                    }
                });
            });
        </script>
    </div>
@endsection
