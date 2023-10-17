<?php
use App\Models\CustomLog;

if (!function_exists('customLog')) {
    function customLog($message, $logData, $userId, $ipAddress) {
        Log::channel('custom_log')->info($message, $logData);

        CustomLog::create([
            'message' => $message,
            'context' => json_encode($logData),
            'user_id' => $userId,
            'ip_address' => $ipAddress,
        ]);
    }
}
