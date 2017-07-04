<?php

namespace Selfreliance\Iusers\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLoginLog extends Model
{
    //

    protected $fillable = [
        'user_id', 'browser', 'ip'
    ];
}
