<?php

namespace Dostontiu\ModelLog\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ModelLogInterface
{
    public function __construct(Model $old_model, Model $new_model);

    public function toArray(Model $model);

    public function makeMessage();

    public function createLog();
}
