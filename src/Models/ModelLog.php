<?php

namespace Dostontiu\ModelLog\Models;

use Illuminate\Database\Eloquent\Model;

class ModelLog extends Model
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('model-log.table_name') ?: parent::getTable();
    }

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
    ];
}
