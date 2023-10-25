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
                <button type="button" class="btn btn-primary btn active">Create Sale</button>
            </a>
            <a href="{{ URL::to('sales/challan') }}">
                <button type="button" class="btn btn-primary btn active">Replacement Challan</button>
            </a>
            
            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="table">
                        <table id="dtBasicExample" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Select</th>
                                    {{-- <th class="text-center">Customer name</th> --}}
                                    <th class="text-center">Customer Company</th>
                                    <th class="text-center">Category Name</th>
                                    <th class="text-center">Brand Name</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Serial Number</th>
                                    <th class="text-center" style="width: 400px; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesPaginate as $key => $value)
                                {{-- @php
                                    dd($value);
                                @endphp --}}
                                    <tr class="odd gradeX">
                                        <td><input type="checkbox" onchange="toggleItem({{$value->id}})" /></td>
                                        {{-- <td class="text-center">{{$value->name}}</td> --}}
                                        <td class="text-center">
                                            @if ($value->custmorData)
                                                <a href="{{ URL::to('clients/view/' . $value->custmorData->id) }}">{{ $value->custmorData->full_name }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$value->category->cat_name}}</td>
                                        <td class="text-center">{{$value->brand_name}}</td>
                                        <td class="text-center">
                                            @if ($value->products)
                                                <a href="{{ URL::to('products/view/' . $value->products->id) }}">{{ $value->products->name }}</a>
                                            @endif
                                        </td>
                                        <td class="text-center">{{$value->sn}}</td>
                                        <td >
                                            <div class="btn-group">
                                                <a class="btn btn-small btn-primary btn-md"
                                                    href="{{ URL::to('sales/view/' . $value->id) }}">Details</a>
                                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-md "><span
                                                        class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ URL::to('sales/form/update/' . $value->id) }}">Edit</a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Some option</a></li>
                                                </ul>
                                            </div>
                                            {{-- <a href="{{ URL::to('sales/sendmailChallan?id=' . $value->id) }}"><button type="button" class="btn btn-success btn-sm"> <i class="fa fa-paper-plane"> Send Mail</i>
                                            </button></a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div >
                    <a id="challan_btn" href="sales/sendmailChallan"><button type="button" class="btn btn-success btn-sm"> <i class="fa fa-paper-plane"> Send Mail</i>
                    </button></a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
        });
        var selectedItems = [];
        function toggleItem(id) {
            console.log(id)
            const itemIndex = selectedItems.findIndex((item) => item === id);
            if (itemIndex > -1) {
                selectedItems.splice(itemIndex, 1);
            } else {
                selectedItems.push(id)
            }
            const elm = document.querySelector('#challan_btn');
            if (elm){
                elm.setAttribute('href', `sales/sendmailChallan?id=${selectedItems.join(',')}`)
            }
        }
    </script>
@endsection
