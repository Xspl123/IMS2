<?php

// app/Models/CustomLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomLog extends Model
{
    protected $table = 'custom_logs';

    protected $fillable = ['message', 'context','user_id', 'ip_address','city','country'];
}

