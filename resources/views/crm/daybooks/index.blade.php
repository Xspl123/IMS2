@extends('layouts.base')

@section('caption', 'Expense Tracker')

@section('title', 'Expense Tracker')

@section('lyric', 'Vert-Age')

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
            <!-- Display total and average expenses -->
            <div class="row">
                <!-- Day-wise Expenses -->
                <div class="col-md-4">
                    <div class="panel panel-default" style="background-color: rgb(235, 172, 96)">
                        <div class="panel-heading">
                            <i class="fa fa-code-fork" aria-hidden="true"></i> Day-wise Expenses
                        </div>
                        <div class="panel-body" style="height: 106px;">
                            <h4 style="font-weight: bold; color: {{ $totalExpenseDayWise == max($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'red' : ($totalExpenseDayWise == min($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'blue' : 'green') }}">Total Expense: ₹{{ $totalExpenseDayWise }}</h4>
                            <h4 style="font-weight: bold; color:rgb(39, 105, 192)">Average Expense: ₹{{ round($averageExpenseDayWise) }}</h4>
                        </div>
                    </div>
                </div>
            
                <!-- Week-wise Expenses -->
                <div class="col-md-4">
                    <div class="panel panel-default" style="background-color: rgb(235, 172, 96)">
                        <div class="panel-heading">
                            <i class="fa fa-code-fork" aria-hidden="true"></i> Week-wise Expenses
                        </div>
                        <div class="panel-body">
                            <h5 style="font-weight: bold; color: {{ $totalExpenseWeekWise == max($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'red' : ($totalExpenseWeekWise == min($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'blue' : 'green') }}">Total Expense: ₹{{ $totalExpenseWeekWise }}</h5>
                            <h4 style="font-weight: bold; color: {{ $averageExpenseWeekWise == max($averageExpenseDayWise, $averageExpenseWeekWise, $averageExpenseMonthWise) ? 'red' : ($averageExpenseWeekWise == min($averageExpenseDayWise, $averageExpenseWeekWise, $averageExpenseMonthWise) ? 'blue' : 'green') }}">Average Expense: ₹{{ round($averageExpenseWeekWise) }}</h4>
                        </div>
                    </div>
                </div>
            
                <!-- Month-wise Expenses -->
                <div class="col-md-4">
                    <div class="panel panel-default " style="background-color: rgb(235, 172, 96)">
                        <div class="panel-heading">
                            <i class="fa fa-code-fork" aria-hidden="true"></i> Month-wise Expenses
                        </div>
                        <div class="panel-body" style="height: 106px;">
                            @php
                            $monthNames = [
                                'January', 'February', 'March', 'April', 'May', 'June',
                                'July', 'August', 'September', 'October', 'November', 'December'
                            ];
                            $currentMonthName = $monthNames[date('n') - 1];
                            $currentWeekName = 'Week ' . Carbon\Carbon::now()->week;
                            @endphp
                            <h5 style="font-weight: bold; color: {{ $totalExpenseMonthWise == max($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'red' : ($totalExpenseMonthWise == min($totalExpenseDayWise, $totalExpenseWeekWise, $totalExpenseMonthWise) ? 'blue' : 'green') }}">Total Expense ({{ $currentMonthName }}): ₹{{ $totalExpenseMonthWise }}</h5>
                            <h4 style="font-weight: bold; color: {{ $averageExpenseMonthWise == max($averageExpenseDayWise, $averageExpenseWeekWise, $averageExpenseMonthWise) ? 'red' : ($averageExpenseMonthWise == min($averageExpenseDayWise, $averageExpenseWeekWise, $averageExpenseMonthWise) ? 'blue' : 'green') }}">Average Expense ({{ $currentMonthName }}): ₹{{ round($averageExpenseMonthWise) }}</h4>
                        </div>
                    </div>
                </div>
            
                <!-- Last Week and Last Month Expenses -->
                <div class="col-md-4">
                    <div class="panel panel-default " style="background-color: rgb(235, 172, 96)">
                        <div class="panel-heading">
                            <i class="fa fa-code-fork" aria-hidden="true"></i> Last Week and Last Month Expenses
                        </div>
                        <div class="panel-body" style="height: 120px;"> <!-- Set the fixed height to 100px -->
                            <h5 style="font-weight: bold; color: {{ $totalExpenseLastWeek == max($totalExpenseLastWeek, $totalExpenseLastMonth) ? 'red' : ($totalExpenseLastWeek == min($totalExpenseLastWeek, $totalExpenseLastMonth) ? 'blue' : 'green') }}">Total Expense (Last Week): ₹{{ $totalExpenseLastWeek }}</h5>
                            <h5 style="font-weight: bold; color:rgb(39, 105, 192)">Average Expense (Last Week): ₹{{ round($averageExpenseLastWeek) }}</h5>
                            <h5 style="font-weight: bold; color: {{ $totalExpenseLastMonth == max($totalExpenseLastWeek, $totalExpenseLastMonth) ? 'red' : ($totalExpenseLastMonth == min($totalExpenseLastWeek, $totalExpenseLastMonth) ? 'blue' : 'green') }}">Total Expense (Last Month): ₹{{ $totalExpenseLastMonth }}</h5>
                            <h5 style="font-weight: bold; color:rgb(39, 105, 192)">Average Expense (Last Month): ₹{{ round($averageExpenseLastMonth) }}</h5>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-code-fork" aria-hidden="true"></i> List of Expenses
                </div>
                <div class="panel-body">
                    <div class="table">
                        <table class="table table-bordered sys_table  table-hover" id="dataTables-example" data-sortable>
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center" style="color: red">Expense</th>
                                    <th class="text-center" style="width:100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($daybooksPaginate as $key => $value)
                                    <tr class="odd gradeX">
                                        <td class="text-center">{{ $value->date }}</td>
                                        <td>
                                            @if ($value->categoryData)
                                                {{ $value->categoryData->name }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $value->description }}</td>
                                        <td class="text-center" style="color: red">₹{{ $value->dr }}</td>

                                        <td style="width:40px;">
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ URL::to('daybooks/view/' . $value->id) }}">More Details</a>
                                                <button data-toggle="dropdown"
                                                    class="btn btn-primary dropdown-toggle btn-sm "><span
                                                        class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a
                                                            href="{{ URL::to('daybooks/form/update/' . $value->id) }}">Manage</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $daybooksPaginate->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
