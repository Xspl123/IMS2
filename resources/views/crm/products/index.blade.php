@extends('layouts.base')

@section('caption', 'List of products')

@section('title', 'List of products')

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
            {{-- <a href="{{ URL::to('products/form/create') }}">
                <button type="button" class="btn btn-primary btn active pb-2" style="margin-bottom: 10px;">Add products</button>
            </a> --}}
            <button class="btn btn-primary btn active pb-2" style="margin-bottom: 10px;">
                <a href="{{ route('export.products') }}" style="color: white;">Download Products List</a>
            </button>
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> List of products
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    <th class="text-center">Barcode</th>
                                    <th class="text-center">Vendor Name</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Product Descriptions</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Brand Name</th>
                                    {{-- @if($productsPaginate->where('rented', 1)->count() > 0)
                                        <th class="text-center">Rent Product</th>
                                    @endif
                                    @if($productsPaginate->where('rented', 1)->count() > 0)
                                        <th class="text-center">Rent Start Date</th>
                                        <th class="text-center">Rent End Date</th>
                                    @endif --}}
                                    {{-- <th class="text-center">Base Price</th>
                                    <th class="text-center">GST Price</th>
                                    <th class="text-center">Price (Incl. GST):</th> --}}
                                    {{-- <th class="text-center">Purchase Product</th> --}}
                                    {{-- <th class="text-center">Status</th> --}}
                                    <th class="text-center" style="width:200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productsPaginate as $key => $value)

                                    <tr class="odd gradeX">
                                        
                                        <td class="text-center">
                                            {!! DNS1D::getBarcodeHTML($value->barcode, 'C128') !!}
                                            {{-- <span>{{ $value->barcode }}</span> --}}
                                        </td>
                                        <td class="text-center">
                                            @if($value->vendor)
                                                <a href="{{ URL::to('vendors/view/' . $value->vendor->id) }}">{{ $value->vendor->name }}</a>
                                            @endif
                                        </td>
                                
                                        <td class="text-center">{{ $value->name }}</td>
                                        <td class="text-center">{{ $value->description }}</td>
                                        <td class="text-center">{{ $value->count }}</td>
                                        <td class="text-center">{{ $value->brand_name }}</td>
                                       
                                        {{-- @if($productsPaginate->where('rented', 1)->count() > 0)
                                            <td class="text-center">{{ $value->rented }}</td>
                                        @endif
                                        @if($value->rented)
                                            <td class="text-center">{{ $value->rent_start_date }}</td>
                                            <td class="text-center">{{ $value->rent_end_date }}</td>
                                        @endif --}}
                                        {{-- <td class="text-center">
                                            <button type="submit" class="btn btn-default">
                                                @if($value->price)
                                                    ₹ {{ Cknow\Money\Money::{App\Models\SettingsModel::getSettingValue('currency')}($value->price)->getAmount() }}
                                                @else
                                                    Yes
                                                @endif
                                            </button>
                                        </td> --}}
                                        {{-- <td class="text-center"><button type="submit" class="btn btn-default">₹ {{ $value->gstAmount }}</button></td> --}}
                                        {{-- <td class="text-center">
                                            <button type="submit" class="btn btn-default">
                                                @if($value->price_with_gst)
                                                    ₹ {{ Cknow\Money\Money::{App\Models\SettingsModel::getSettingValue('currency')}($value->price_with_gst)->getAmount() }}
                                                @else
                                                    Yes
                                                @endif
                                            </button>
                                        </td> --}}

                                        {{-- <td class="text-center">{{ $value->purchase }}</td> --}}
                                        {{-- <td class="text-center">
                                            @if($value->is_active)
                                                <label class="switch">
                                                    <input type="checkbox" onchange='window.location.assign("{{ URL::to('products/set-active/' . $value->id . '/0') }}")' checked>
                                                    <span class="slider"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox" onchange='window.location.assign("{{ URL::to('products/set-active/' . $value->id . '/1') }}")'>
                                                    <span class="slider"></span>
                                                </label>
                                            @endif
                                        </td> --}}
                                        <td class="text-right" style="text-align: center">
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-primary" href="{{ URL::to('products/view/' . $value->id) }}">Details</a>
                                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ URL::to('products/form/update/' . $value->id) }}">Edit</a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Some option</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $productsPaginate->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
