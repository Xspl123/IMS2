<?php
    namespace App\Services;

    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;
    use App\CustomLog; // Replace with the actual model name for the custom log table

    class CustomLogService
    {
        public function logAndInsert($message, $logData, $userId, $ipAddress)
        {
            $userId = Auth::id();
            $ipAddress = $request->ip();
            
            Log::channel('custom_log')->info($message, $logData);

            CustomLog::create([
                'message' => $message,
                'context' => json_encode($logData),
                'user_id' => $userId,
                'ip_address' => $ipAddress,
            ]);
        }
    }
