<?php

namespace Selfreliance\Iusers\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLoginLogs extends Model
{
    //

    protected $table = 'users__login_logs';

    protected $fillable = [
        'user_id', 'browser', 'ip'
    ];
}
