@extends('layouts.base')

@section('caption', 'List of Related Data')

@section('title', '')

@section('content')

<div>
    <a href="{{url('/')}}"><button type="button" class="btn btn-primary">Back</button></a>
</div>
<br>
<table class="data-table">
    <thead>
        <tr>
            <th>Status</th>
            <th>Product SN</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Brand Name</th>
            <th>Customer Company</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($relatedData as $data)
            <tr>
                <td><strong>{{ $data->status }}</strong></td>
                <td>{{ $data->sn }}</td>
                <td>{{ $data->products->name }}</td>
                <td>{{ $data->category->cat_name }}</td>
                <td>{{ $data->brand_name }}</td>
                <td>{{ $data->custmorData->full_name}}</td>
                <td>
                    @if ($data->status === 'Returned' || 'replacement')
                        {{ $data->updated_at->format('F j, Y') }}
                    @else
                        {{ $data->created_at->format('F j, Y') }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>



<style>
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th, .data-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    .data-table th {
        background-color: #f2f2f2;
    }

    .data-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .data-table tr:hover {
        background-color: #ddd;
    }
</style>
@endsection
