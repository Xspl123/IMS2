@extends('layouts.base')

@section('caption', 'List of Expence Report')

@section('title', 'List of Expence')

@section('lyric', 'Vert-Age')

@section('content')
<div class="report-container">
    
    <table class="table table-bordered table-hover report-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Dr</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daywiseExpense as $dayExpense)
                <tr>
                    <td>{{ $dayExpense->created_at->format('Y-m-d') }}</td>
                    <td>₹ {{ $dayExpense->total_expense }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total Expense</th>
                <th>₹ {{ $totalExpense }}</th>
            </tr>
        </tfoot>
    </table>
    <button id="downloadChart">&#x1F4BE; </button>
    <canvas id="expenseChart" width="200" height="50"></canvas>

    <a id="downloadLink" style="display: none; color: #999;" download="expense_chart.png">&#x1F4BE;</a>

</div>

<script>
    var ctx = document.getElementById('expenseChart').getContext('2d');
    var labels = {!! json_encode($daywiseExpense->pluck('created_at')->map(function ($item) {
        return $item->format('d-M-Y'); // Format the date as needed
    })) !!};
    var expenseData = {!! json_encode ($daywiseExpense->pluck('total_expense')) !!};

    var expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Dr',
                data: expenseData,
                borderColor: '#FF9933', // Bhagwa color
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        parser: 'YYYY-MM-DD', // Set the date format to match the labels
                        unit: 'day'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Download Chart Button Click Handler
    document.getElementById('downloadChart').addEventListener('click', function () {
        var imageUrl = expenseChart.toBase64Image();
        document.getElementById('downloadLink').href = imageUrl;
        document.getElementById('downloadLink').click();
    });
</script>

@endsection
