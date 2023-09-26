@extends('layouts.base')

@section('caption', '')

@section('title', 'Information about product')


@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-6">
            @if(session()->has('message_success'))
                <div class="alert alert-success">
                    <strong>Well done!</strong> {{ session()->get('message_success') }}
                </div>
            @elseif(session()->has('message_danger'))
                <div class="alert alert-danger">
                    <strong>Danger!</strong> {{ session()->get('message_danger') }}
                </div>
            @endif
            <br/>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2 class="product_caption">Information About Product</h2>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Basic information</a>
                        </li>
                        <div class="pull-right">
                            <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">
                                Delete this product <i class="fa fa-trash-o"></i>
                            </button>
                        </div>
                    </ul>
                    <div class="tab-pane fade active in" id="home">
                        <h4></h4>
                       
                        <div class="product_row">
                        <table class="table table-striped table_Information table-bordered">
                            <tbody class="text-right">
                                <tr>
                                    <th>Barcode</th>
                                    <td>{{ $product->barcode }}</td>
                                    <th>Vendor Name</th>
                                    <td class="text-center">
                                        @if($product->vendor)
                                            <a href="{{ URL::to('vendors/view/' . $product->vendor->id) }}">{{ $product->vendor->name }}</a>
                                        @endif
                                    </td>
                                    <th>Product Name</th>
                                    <td>{{ $product->name }}</td>
                                    <th>Product Description</th>
                                    <td>{{ $product->description  }}</td>
                                    <th>Price (Incl. GST)</th>
                                   <td>₹ {{ $product->price_with_gst }}</td>
                                </tr>
                                <tr>
                                    <th>Brand Name</th>
                                    <td>{{ $product->brand_name  }}</td>
                                    <th>Quentity</th>
                                    <td>{{ $product->count  }}</td>
                                    <th>Base Price</th>
                                    <td> ₹ {{ $product->price }}</td>
                                    <th>Purchase Pruduct</th>
                                    <td>{{ $product->purchase  }}</td>
                                    <th>Rent Product</th>
                                    <td>{{ $product->rented  }}</td>
                                    
                                </tr>
                                <tr>
                                   
                                    <th>Rent Start Date</th>
                                    <td>{{ $product->rent_start_date  }}</td>
                                    <th>Rent End Date</th>
                                    <td>{{ $product->rent_end_date  }}</td>
                                    <th>Status</th>
                                    <td>{{ $product->is_active ? 'Active' : 'Deactivate' }}</td>
                                    <th>GST Amount</th>
                                    {{-- <td>₹ @php echo $product->gstAmount; @endphp </td> --}}
                                    <td>₹ {{$product->gstAmount}}</td>
                                </tr>
                                <tr>
                                   
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">You want delete this product?</h4>
                </div>
                <div class="modal-body">
                    Action will delete permanently this product.
                </div>
                <div class="modal-footer">
                    {{ Form::open(['url' => 'products/delete/' . $product->id,'class' => 'pull-right']) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete this product', ['class' => 'btn btn-small btn-danger']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
