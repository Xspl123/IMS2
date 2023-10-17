@extends('layouts.base')

@section('caption', 'List of products')

@section('title', 'List of products')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <button class="btn btn-primary btn active pb-2" style="margin-bottom: 10px;">
                <a href="{{ route('export.products') }}" style="color: white;">Download Products List</a>
            </button>

            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-keyboard-o" aria-hidden="true"></i> List of Products
                </div>
                <div class="panel-body">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">
                                <span class="badge badge-primary" style="background-color: blue; color: white;">
                                    Total Products: {{ $productCount }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="table">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                        <thead>
                            <tr>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">Vendor Name</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Product Category</th>
                                <th class="text-center">Product Serial No.</th>
                                <th class="text-center">Product Descriptions</th>
                                <th class="text-center">Product Brand Name</th>
                                <th class="text-center">Product Type</th>
                                <th class="text-center" style="width: 100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productsPaginate as $key => $value)
                                @if ($value->is_active) <!-- Check if the product is active -->
                                    <tr class="odd gradeX">
                                        <td class="text-center">
                                            <input type="checkbox" name="barcode[]" value="{{ $value->barcode }}">
                                            {!! DNS1D::getBarcodeHTML($value->barcode, 'C128') !!}
                                        </td>
                                        
                                        <td class="text-center">
                                            @if ($value->vendor)
                                                <a href="{{ URL::to('vendors/view/' . $value->vendor->id) }}">{{ $value->vendor->name }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $value->name }}</td>
                                        <td class="text-center">{{ $value->category->cat_name }}</td>
                                        <td class="text-center">{{ $value->product_serial_no }}</td>
                                        <td class="text-center">{{ $value->description }}</td>
                                        <td class="text-center">{{ $value->brand_name }}</td>
                                        <td class="text-center">{{ $value->product_type }}</td>
                                        <td style="width: 40px;">
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-primary"
                                                    href="{{ URL::to('products/view/' . $value->id) }}">Details</a>
                                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm "><span
                                                        class="caret"></span></button>
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
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {!! $productsPaginate->render() !!}
            </div>
        </div>
    </div>
@endsection
