<?php

namespace Dostontiu\ModelLog;

use Illuminate\Database\Eloquent\Model;

class ModelLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
}
