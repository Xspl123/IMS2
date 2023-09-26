@extends('layouts.base')

@section('caption', 'Income vs Expense')

@section('title', 'Income vs Expense Report')

@section('lyric', 'Vert-Age')

@section('content')
<div class="report-container">
    
    <table class="table table-bordered table-hover report-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Dr (Expense)</th>
                <th>Cr (Income)</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            @if ($row->dr > 0 || $row->cr > 0 || $row->bal > 0)
                <tr>
                    <td>{{ $row->created_at->format('d-M-Y H:i') }}</td>
                    <td> {{ $row->dr > 0 ? $row->dr : '' }}</td>
                    <td> {{ $row->cr > 0 ? $row->cr : '' }}</td>
                    <td> {{ $row->bal > 0 ? $row->bal : '' }}</td>
                </tr>
            @endif
        @endforeach
             
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th>₹ {{ $sumexpense }}</th>
                <th>₹ {{ $sumIncome }}</th> 
                <th>₹ {{ $latestBalance }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
   
    <canvas id="comparisonChart" width="300" height="100"></canvas>

</div>

<script>
    var ctx = document.getElementById('comparisonChart').getContext('2d');
    var data = {!! json_encode($data) !!}; 
    var labels = data.map(item => {
        var date = new Date(item.created_at);
        var options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    });

    var expenseData = data.map(item => item.dr);
    var incomeData = data.map(item => item.cr);
    var balanceData = data.map(item => item.bal);


    var comparisonChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Dr (Expense)',
                    data: expenseData,
                    borderColor: 'red',
                    fill: false,
                },
                {
                    label: 'Cr (Income)',
                    data: incomeData,
                    borderColor: 'green',
                    fill: false,
                },
                {
                    label: 'Bal (Balance)',
                    data: balanceData,
                    borderColor: 'blue',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        tooltipFormat: 'MMM D, YYYY, h:mm a', // Format for tooltip
                        displayFormats: {
                            hour: 'MMM D, YYYY, h:mm a' // Format for x-axis labels
                        }
                    },
                    title: {
                        display: true,
                        text: 'Date and Time'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount'
                    }
                }
            }
        }
    });
</script>
@endsection
