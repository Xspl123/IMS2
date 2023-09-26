@extends('layouts.base')

@section('caption', 'List of Income Report')

@section('title', 'List of Income')

@section('lyric', 'Vert-Age')

@section('content')
<div class="report-container">
    
    <table class="table table-bordered table-hover report-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Cr</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daywiseIncome as $dayIncome)
                <tr>
                    <td>{{ $dayIncome->created_at->format('Y-m-d') }}</td>
                    <td>₹ {{ $dayIncome->total_income }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total Income</th>
                <th>₹ {{ $totalIncome }}</th>
            </tr>
        </tfoot>
    </table>
    <button id="downloadChart">&#x1F4BE; </button>
    <canvas id="incomeChart" width="200" height="50"></canvas>
  
    <a id="downloadLink" style="display: none; color: #999;" download="income_chart.png">&#x1F4BE;</a>

</div>

<script>
    var ctx = document.getElementById('incomeChart').getContext('2d');
    var labels = {!! json_encode($daywiseIncome->pluck('created_at')->map(function ($item) {
        return $item->format('d-M-Y'); // Format the date as needed
    })) !!};
    var incomeData = {!! json_encode ($daywiseIncome->pluck('total_income')) !!};

    var incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cr',
                data: incomeData,
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
        var imageUrl = incomeChart.toBase64Image();
        document.getElementById('downloadLink').href = imageUrl;
        document.getElementById('downloadLink').click();
    });
</script>

@endsection
