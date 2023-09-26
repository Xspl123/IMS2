@extends('layouts.base')

@section('caption', 'Rents Details')

@section('title', 'Rents Details')

@section('content')
    <div class="">
        <div class="col-md-12">
            @if(session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <a href="{{ URL::to('rents/form/create') }}">
                <button type="button" class="btn btn-primary btn active">Add Rental Product</button>
            </a>
            
            
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> Rents Details
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Coustmer name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Brand Name</th>
                                <th class="text-center">Date of Payment</th>
                                <th class="text-center">Rent Start Date</th>
                                <th class="text-center">Rent End Date</th> 
                                <th class="text-center">GST Amount</th>
                                <th class="text-center">Total Amount</th>
                                {{-- <th class="text-center">Profit</th> <!-- Add the Profit column --> --}}
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width:200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rentsPaginate as $key => $value)
                                <tr class="odd gradeX">
                                    <td class="text-center">{{ $value->name }}</td>
                                    <td class="text-center">
                                        @if ($value->custmorData)
                                           <a href="{{ URL::to('clients/view/' . $value->custmorData->id) }}">{{ $value->custmorData->full_name }}</a>
                                        @endif
                                     </td>
                                    <td class="text-center">{{ $value->quantity }}</td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-default">
                                            @if($value->price)
                                                ₹ {{ Cknow\Money\Money::{App\Models\SettingsModel::getSettingValue('currency')}($value->price)->getAmount() }}
                                            @else
                                                Yes
                                            @endif
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if ($value->products)
                                           <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->name }}</a>
                                        @endif
                                     </td> 
                                     <td class="text-center">
                                        @if ($value->products)
                                           <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->brand_name }}</a>
                                        @endif
                                     </td> 
                                    <td class="text-center">{{ $value->date_of_payment }}</td>
                                    <td class="text-center">{{ $value->rent_start }}</td>
                                    <td class="text-center">{{ $value->rent_end }}</td>
                                    {{-- <td class="text-center">
                                        @php
                                            $purchaseAmount = $value->products->purchase_price * $value->quantity; // Calculate the purchase amount
                                        @endphp
                                        ₹ {{ $purchaseAmount }}
                                    </td> --}}
                                    <td class="text-center">
                                      <button type="submit" class="btn btn-default">  
                                        ₹ {{ $value->price * $value->quantity * (18 / 100) }}
                                      </button>  
                                    </td>
                                    <td class="text-center">  
                                        @php
                                        // Calculate the GST amounts based on the gst_rate field in the $value object                                   
                                        $sgstAmount = 0;
                                        $cgstAmount = 0;

                                        if ($value->gst_rate === 'igst') {
                                            // Assuming IGST rate is 18%
                                            $igstRate = 0.18;
                                            $sgstAmount = $value->price * $value->quantity * $igstRate;
                                            $cgstAmount = $sgstAmount;
                                        } elseif ($value->gst_rate === 'sgst_cgst') {
                                            // Assuming SGST and CGST rates are both 9%
                                            $sgst_cgst_Rate = 0.09;
                                            $sgstAmount = $value->price * $value->quantity * $sgst_cgst_Rate;
                                            $cgstAmount = $sgstAmount;
                                        } elseif ($value->gst_rate === 'sgst') {
                                            // Assuming SGST rate is 9%
                                            $sgstRate = 0.09;
                                            $sgstAmount = $value->price * $value->quantity * $sgstRate;
                                        } elseif ($value->gst_rate === 'cgst') {
                                            // Assuming CGST rate is 9%
                                            $cgstRate = 0.09;
                                            $cgstAmount = $value->price * $value->quantity * $cgstRate;
                                        }

                                        // Calculate the total amount including both SGST and CGST
                                        $totalAmount = $value->price * $value->quantity + $sgstAmount + $cgstAmount;
                                    @endphp
                                        <button type="submit" class="btn btn-default">
                                            ₹ {{ $totalAmount }}
                                        </button>
                                    </td>
                                     
                                    {{-- <td class="text-center">
                                        @php
                                            $profit = $totalAmount - $purchaseAmount; // Calculate the profit
                                        @endphp
                                        ₹ {{ $profit }}
                                    </td> --}}
                                    {{-- <td class="text-center">
                                            @if($value->is_active)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           onchange='window.location.assign("{{ URL::to('rents/set-active/' . $value->id . '/0') }}")' checked>
                                                    <span class="slider"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           onchange='window.location.assign("{{ URL::to('rents/set-active/' . $value->id . '/1') }}")'>
                                                    <span class="slider"></span>
                                                </label>
                                            @endif
                                    </td> --}}
                                    <td class="text-center">{{ $value->status }}</td>

                                    <td class="text-right" style="text-align: center">
                                        <div class="btn-group">
                                            <a class="btn btn-small btn-primary"
                                               href="{{ URL::to('rents/view/' . $value->id) }}">More information</a>
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('rents/form/update/' . $value->id) }}">Edit</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Some option</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('rents/invoice/'.$value->id) }}">
                                            <button type="button" class="btn btn-primary btn active">Invoice</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $rentsPaginate->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
