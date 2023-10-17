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
            
            <div class="panel panel-default">

                <div class="panel-body">
  
                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    <th><span>Customer Name </span></th>
                                    <th><span>Customer Company</span></th>
                                    <th><span>Product name </span></th>
                                    <th><span>Brand </span></th>
                                    <th><span>Category </span></th>
                                    <th><span>Serial Number </span></th>
                                    <th class="text-center" style="width: 100px; text-align:center">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($challanInvoice as $key => $item)
                                
                                <tr>
                                    <!-- Display the corresponding chalans data -->
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->custmorData->full_name}}</td>
                                    <td>{{ $item->products->name }}</td>
                                    <td>{{ $item->brand_name}}</td>
                                    <td>{{ $item->category->cat_name}}</td>  
                                    <td>{{ $item->sn }}</td>
                                    {{-- <td><a class="btn btn-small btn-primary" href="{{ URL::to('sales/challan-invoice/' . $item->id) }}">Challan &nbsp;</a> --}}
                                    <td><a class="btn btn-small btn-primary" href="{{ URL::to('sales/sendmailChallan/' . $item->id) }}">Send Mail &nbsp;</a></td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
