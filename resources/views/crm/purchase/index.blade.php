@extends('layouts.base')

@section('caption', 'List of Purchase')

@section('title', 'List of Purchase')

@section('content')
    <div class="row">
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
            <a href="{{ URL::to('purchase/form/create') }}">
                <button type="button" class="btn btn-primary btn active">Add purchase item</button>
            </a>
            <br><br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> List of purchase item
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Date of Payment</th>
                                    <th class="text-center">Gst Rate</th>
                                    <th class="text-center">Total price</th>
                                    <th class="text-center">GST Amount</th> <!-- Add this column -->
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width:200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesPaginate as $key => $value)
                                    <tr class="odd gradeX">
                                        <td class="text-center">{{ $value->name }}</td>
                                        <td class="text-center">{{ $value->quantity }}</td>
                                        <td class="text-center">
                                            @if ($value->products && $value->products->first())
                                                ₹ {{ $value->products->first()->price }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($value->products)
                                               <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->name }}</a>
                                            @endif
                                         </td>
                                        <td class="text-center">{{ $value->date_of_payment }}</td>
                                        <td class="text-center">{{ $value->gst_rate }}</td>
                                        <td class="text-center">
                                            @if ($value->products && $value->products->first())
                                                ₹ {{ $value->quantity * $value->products->first()->price }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center"> <!-- Calculate and display GST Amount -->
                                            @if ($value->products && $value->products->first())
                                                ₹ {{ ($value->quantity * $value->products->first()->price) * ($value->gst_rate / 100) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($value->is_active)
                                                <label class="switch">
                                                    <input type="checkbox" onchange='window.location.assign("{{ URL::to('purchase/set-active/' . $value->id . '/0') }}")' checked>
                                                    <span class="slider"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox" onchange='window.location.assign("{{ URL::to('purchase/set-active/' . $value->id . '/1') }}")'>
                                                    <span class="slider"></span>
                                                </label>
                                            @endif
                                        </td>
                                        <td class="text-right" style="text-align: center">
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-primary" href="{{ URL::to('purchase/view/' . $value->id) }}">More Information</a>
                                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ URL::to('purchase/form/update/' . $value->id) }}">Edit</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Option</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $salesPaginate->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
