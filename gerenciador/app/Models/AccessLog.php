<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AccessLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_access_logs';
    protected $fillable = ['id_access_logs', 'id', 'ip_address', 'user_agent'];
}


