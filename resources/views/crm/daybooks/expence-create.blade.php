@extends('layouts.base')

@section('caption', 'New Expence')

@section('title', 'New Expence')

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
                <div class="row mt-4">
                    <div class="col-md-12 mt-4">
                        <button type="button" class="btn btn-primary pull-right " data-toggle="modal" data-target="#myModal"><i class="fa fa-money" aria-hidden="true" style="font-size:20px;"></i> {{$wallet->amount}}</button>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            {{ Form::open(['route' => 'processStoreExpense']) }}
                            <input type="hidden" name="wallet_amt" value="{{$wallet->amount}}">
                            <div class="form-group input-row ">
                                {{ Form::label('payer', 'Payer Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        {{ Form::text('payer', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'required' => 'required'])}}

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('date', 'Date') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') , 'required' => 'required' ]) }}
                                </div>
                            </div>                            
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('description', 'Description') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::text('description', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') , 'required' => 'required']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('amount', 'Amount') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') , 'required' => 'required']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('category', 'Category') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::select('category', $dataOfCategories, null, ['class' => 'form-control', 'placeholder' => 'Choose categories']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('payee', 'Payee Name') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::text('payee', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText') , 'required' => 'required']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group input-row">
                                {{ Form::label('method', 'Method') }}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                    {{ Form::select('method', $dataOfPmethod, null, ['class' => 'form-control', 'placeholder' => 'Select Payment Method']) }}
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
    {{-- *************** open modal*** --}}

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Recharge your wallet</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group input-row">
                        <div class="input-group">
                            <label>Enter Amount</label>
                            <input type="number" name="amount" class="form-control">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" data-dismiss="modal">Submit</button>
            </div>
        </form>
          </div>
        </div>
      </div>
        <script>
            $(document).ready(function () {

                var validator = new formValidator({
                    //this function adds an error message to a form field
                    addError: function (field, message) {
                        //get existing error message field
                        var error_message_field = $('.error_message', field.parent('.input-group'));

                        //if the error message field doesn't exist yet, add it
                        if (!error_message_field.length) {
                            error_message_field = $('<span/>').addClass('error_message');
                            field.parent('.input-group').append(error_message_field);
                        }

                        error_message_field.text(message).show(200);
                        field.addClass('error');
                    },
                    //this removes an error from a form field
                    removeError: function (field) {
                        $('.error_message', field.parent('.input-group')).text('').hide();
                        field.removeClass('error');
                    },
                    
                    onErrors: function (errors, event) {
                        //errors is an array of objects, each containing a 'field' and 'message' parameter
                    },
                    //this defines the actual validation rules
                   
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
    </div>
@endsection


