@extends('layouts.base')

@section('caption', 'Sales Details')

@section('title', 'Sales Details')

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
            <a href="{{ URL::to('sales/form/create') }}">
                <button type="button" class="btn btn-primary btn active">Add</button>
            </a>
            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    <th class="text-center">Customer name</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Serial Number</th>
                                    <th class="text-center">Brand Name</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 200px; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesPaginate as $key => $value)
                                    <tr class="odd gradeX">
                                        <td class="text-center">
                                            
                                            @if ($value->custmorData)
                                            
                                                <a href="{{ URL::to('clients/view/' . $value->custmorData->id) }}">{{ $value->custmorData->full_name }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($value->products)
                                                <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->name }}</a>
                                            @endif
                                        </td>
                                        <td>{{$value->sn}}</td>
                                        
                                        <td class="text-center">
                                            @if ($value->products)
                                                <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->brand_name }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $value->status }}</td>
                                        <td class="text-center" style="text-align: center">
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-primary" href="{{ URL::to('sales/view/' . $value->id) }}">View &nbsp;</a>
                                                <a class="btn btn-small btn-success" href="{{ URL::to('sales/form/update/' . $value->id) }}">Edit</a>
                                                <form action="{{ route('processDeleteSale', ['clientId' => $value->id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</button>
                                                </form>
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
