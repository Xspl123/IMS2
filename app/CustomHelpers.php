<?php

    if (!function_exists('calculateAverage')) {
        function calculateAverage($total, $count) {
            return $count > 0 ? $total / $count : 0;
        }
    }

    if (!function_exists('calculateExpensesBetweenDates')) {
        function calculateExpensesBetweenDates($startDate, $endDate) {
            // Your calculation logic for expenses between dates here...
        }
    }
?>