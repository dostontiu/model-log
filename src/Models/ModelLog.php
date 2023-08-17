<?php

namespace Dostontiu\ModelLog\Models;

use Illuminate\Database\Eloquent\Model;

class ModelLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
}
