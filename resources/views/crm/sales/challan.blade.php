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
                                    <th>Approved By</th>
                                    <th>DI</th>
                                    <th>DISN</th>
                                    {{--<th>DItem vendor</th>
                                     <th>DItem remark</th> --}}
                                    <th>RI</th>
                                    <th>RISN</th>
                                    {{-- <th>RIvendor</th>
                                    <th>RIcustmor</th>
                                    <th>RIRemark</th> --}}
                                    <th class="text-center" style="width: 100px; text-align:center">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chalanData as $key => $item)
                                
                                <tr>
                                    <!-- Display the corresponding chalans data -->
                                    <td>{{ $item->approved_By ?? 'N/A' }}</td>
                                    <td>{{ $item->defulty_product_name ?? 'N/A' }}</td>
                                    <td>{{ $item->defulty_product_sn ?? 'N/A' }}</td>
                                    {{-- <td>{{ $item->DefultyvendorData->name ?? 'N/A' }}</td>
                                    <td>{{ $item->defulty_product_remark ?? 'N/A' }}</td> --}}
                                    <td>{{ $item->products->name ?? 'N/A' }}</td>
                                    <td>{{ $item->replacement_product_serial ?? 'N/A' }}</td>
                                    {{-- <td>{{ $item->vendorData->name ?? 'N/A' }}</td>
                                    <td>{{ $item->custmorData->full_name ?? 'N/A' }}</td>
                                    <td>{{ $item->replacement_Remark ?? 'N/A' }}</td> --}}
                                    {{-- <td><a class="btn btn-small btn-primary" href="{{ URL::to('sales/challan-invoice/' . $item->id) }}">Challan &nbsp;</a> --}}
                                    <td><a class="btn btn-small btn-primary" href="{{ URL::to('sales/challan-invoice-mail/' . $item->id) }}">Send Mail &nbsp;</a></td>

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
