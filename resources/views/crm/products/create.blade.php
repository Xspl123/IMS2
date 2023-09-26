@extends('layouts.base')
@section('caption', 'Add Products')
@section('title', 'Add Products')
@section('lyric', '')
@section('content')
@if($errors->any())
<div class="alert alert-danger">
   <strong>Danger!</strong> {!! implode('', $errors->all('
   <div>:message</div>
   ')) !!}
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
      {{ Form::open(['route' => 'processStoreProduct']) }}
      <div class="form-group col-lg-4">
         {{ Form::label('barcode', 'Barcode') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::text('barcode', null, ['class' => 'form-control', 'placeholder' => 'Barcode', 'id' ,'readonly' => 'barcode']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('vendor_id', 'Assign Vendor') }}
         <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-handshake-o"></i></span>
            {{ Form::select('vendor_id', $dataOfVendors, null, ['class' => 'form-control', 'placeholder' => 'Select a Vendor Name']) }}
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('name', 'Product Name') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('brand_name', 'Brand Name') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::select('brand_name', [
            'AlphaGear' => 'AlphaGear',
            'BetaTech' => 'BetaTech',
            'CyberNova' => 'CyberNova',
            'DeltaFusion' => 'DeltaFusion',
            'EpicQuest' => 'EpicQuest',
            'FutureTech' => 'FutureTech',
            'GalaxyGadgets' => 'GalaxyGadgets',
            'HorizonHaven' => 'HorizonHaven',
            'InfinityTech' => 'InfinityTech',
            'JupiterJunction' => 'JupiterJunction',
            // Add more brand names as needed
            ], null, ['class' => 'form-control', 'placeholder' => 'Select a Brand Name']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('price', 'Base Price') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::text('price', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'price']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
        {{ Form::label('count', 'Quantity') }}
        <div class="input-group">
            <span class="input-group-addon">
                <input type="number" class="form-control" name="count" min="1" max="1" step="1" placeholder="1">
            </span>
        </div>
    </div>
    
      <div class="form-group col-lg-4">
         {{ Form::label('gst_rate', 'GST Rate') }}
         <div class="input-group">
            <span class="input-group-addon">%</span>
            {{ Form::select('gst_rate', ['igst' => 'IGST 18%', 'sgst' => 'SGST 9%', 'cgst' => 'CGST 9%', 'sgst_cgst' => 'SGST + CGST 9%'], null, ['class' => 'form-control', 'placeholder' => 'Select GST Rate', 'name' => 'gst_rate', 'id' => 'gst_rate']) }}
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('gstAmount', 'GST Amount') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::text('gstAmount', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'gstAmount', 'readonly', 'id' => 'gstAmount']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('total_amount', 'Total Amount') }}
         <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
            {{ Form::text('total_amount', null, ['class' => 'form-control', 'readonly', 'name' => 'total_amount', 'id' => 'total_amount']) }}
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('price_with_gst', 'Price (Incl. GST):  ') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::text('price_with_gst', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText'), 'name' => 'price_with_gst', 'readonly', 'id' => 'price_with_gst']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('rented', 'Rented Product') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::select('rented', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control', 'placeholder' => 'Select Option']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4 rent-dates" style="display: none;">
         {{ Form::label('rent_start_date', 'Rent Start Date') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::date('rent_start_date', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4 rent-dates" style="display: none;">
         {{ Form::label('rent_end_date', 'Rent End Date') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::date('rent_end_date', null, ['class' => 'form-control', 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('purchase', 'Purchase Product') }}
         <div class="input-group">
            <span class="input-group-addon">
            {{ Form::select('purchase', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control', 'placeholder' => 'Select Option']) }}
            </span>
         </div>
      </div>
      <div class="form-group col-lg-4">
         {{ Form::label('description', 'Product Description') }}
         <div class="">
            <div class="">
               {{ Form::textarea('description', null, ['class' => 'form-control Product_Description',  'rows' =>5, 'placeholder' => App\Traits\Language::getMessage('messages.InputText')]) }}
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-12 validate_form text-center">
      {{ Form::submit('Add product', ['class' => 'btn btn-primary']) }}
   </div>
   {{ Form::close() }}
</div>
<script>
   $(document).ready(function () {
   
       // Call the function to set the initial total amount value
       calculateTotalPrice();
   
       // Add event listeners to the required fields
       $('input[name="count"], select[name="gst_rate"], input[name="price"]').on('change', function () {
           calculateTotalPrice();
       });
   
       // Function to calculate total amount and GST amount
       function calculateTotalPrice() {
           var price = parseFloat($('input[name="price"]').val());
           var quantity = parseInt($('input[name="count"]').val());
           var gstRate = $('select[name="gst_rate"]').val();
   
           if (!isNaN(price) && !isNaN(quantity)) {
               var gstAmount = 0;
   
               if (gstRate === 'igst') {
                   gstAmount = price * quantity * 0.18; // IGST 18%
               } else if (gstRate === 'sgst' || gstRate === 'cgst') {
                   gstAmount = price * quantity * 0.09; // SGST 9% or CGST 9%
               } else if (gstRate === 'sgst_cgst') {
                   gstAmount = (price * quantity * 0.09) * 2; // SGST 9% + CGST 9%
               }
   
            //    var totalPrice = price * quantity + gstAmount;
            //    $('#gstAmount').val(gstAmount.toFixed(2));
            //    $('#total_amount').val(totalPrice.toFixed(2));
            //    $('#price_with_gst').val((totalPrice).toFixed(2));

            var totalPrice = price * quantity + gstAmount;
            var roundedGstAmount = gstAmount.toFixed(2);
            var roundedTotalPrice = totalPrice.toFixed(2);

            $('#gstAmount').val(roundedGstAmount);
            $('#total_amount').val(roundedTotalPrice);
            $('#price_with_gst').val(roundedTotalPrice);

           }
       }
       // Create formValidator object
   
       $('input[name=barcode]').on('blur', function () {
           var barcodeInput = $(this);
           if (!barcodeInput.val()) {
               var randomBarcode = Math.floor(100000 + Math.random() * 900000);
               barcodeInput.val(randomBarcode);
           }
       });
       var validator = new formValidator({
           // Error message handling functions
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
               $('.error_message', field.parent('.input-group')).text('').hide();
               field.removeClass('error');
           },
           onErrors: function (errors, event) {
               // Handle errors, e.g., display summary message, scroll to the first error, etc.
           },
           // Validation rules
           rules: {
               'barcode': {
                   'field': $('input[name=barcode]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "A name is required.";
                       }
                   }
               },
   
               'name': {
                   'field': $('input[name=name]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "A name is required.";
                       }
                   }
               },
               'count': {
                   'field': $('input[name=count]'),
                   'validate': function (field, event) {
                       if (event === 'blur' && !field.val()) field.addClass('success');
                       if (!field.val()) throw "A count is required.";
                       var count_pattern = /[0-9]$/i;
                       if (!count_pattern.test(field.val())) {
                           throw "Please write a valid count number.";
                       }
                   }
               },
               'price': {
                   'field': $('input[name=price]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "A price is required.";
                       }
                   }
               },
               'purchase': {
                   'field': $('select[name=purchase]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "Please select an option.";
                       }
                   }
               },
               'rented': {
                   'field': $('select[name=rented]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "Please select an option.";
                       }
                   }
               },
               'rent_start_date': {
                   'field': $('input[name=rent_start_date]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "A rent start date is required.";
                       }
                   }
               },
               'rent_end_date': {
                   'field': $('input[name=rent_end_date]'),
                   'validate': function (field, event) {
                       if (!field.val()) {
                           throw "A rent end date is required.";
                       }
                   }
               },
           }
       });
   
       // Show/hide rent start date and rent end date fields based on rented product selection
       $('select[name=rented]').on('change', function () {
           var rentDatesFields = $('.rent-dates');
           if ($(this).val() === '1') {
               rentDatesFields.show();
           } else {
               rentDatesFields.hide();
           }
       });
   });
</script>
@endsection