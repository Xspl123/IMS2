@extends('layouts.base')

@section('caption', 'Replacement Product Details')

@section('title', 'Replacement Product Details')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="table">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    
                                    <th class="text-center">Replacement Item</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Replacement Product Remark </th>
                                    <th class="text-center">Defected Product Sn.</th>
                                    <th class="text-center">Replacement Product Sn.</th>
                                    <th class="text-center">Approved By</th>
                                    <th class="text-center">Defected Date</th>
                                    <th class="text-center">Replacement Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $key => $data)
                                <tr>
                                    <td>{{ $dataWithPluckOfProducts[$key] ?? 'N/A' }}</td> 
                                    <td>{{ $data->full_name ?? 'N/A' }}</td>
                                    <td>{{ $data->replace_remark }}</td>
                                    <td>{{ $data->sn }}</td>
                                    <td>{{ $data->replacement_product_sn }}</td>
                                    <td>{{ $data->approved_by }}</td>
                                    <td>{{ $data->formatted_created_at }}</td>
                                    <td>{{ $data->formatted_updated_at }}</td>
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
