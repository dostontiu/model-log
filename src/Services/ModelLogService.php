<?php

namespace Dostontiu\ModelLog\Services;

use Dostontiu\ModelLog\Interfaces\ModelLogInterface;
use Dostontiu\ModelLog\Models\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

abstract class ModelLogService implements ModelLogInterface
{
    public $model_name;
    public $model_id;
    public $before;
    public $after;
    public $hiddenColumns = [];

    public function __construct(Model $old_model, Model $new_model)
    {
        $this->model_name = $new_model::class;
        $this->model_id = $new_model->id;
        $this->before = $this->toArray($old_model);
        $this->after = $this->toArray($new_model);

        return $this->createLog();
    }

    public function makeMessage()
    {
        $message = "Change ";
        $array_keys = array_keys($this->before);
        $change_item = 0;
        foreach ($array_keys as $key) {
            if ($this->before[$key] != $this->after[$key] && !in_array($key, $this->hiddenColumns)) {
                $comma = $change_item > 0 ? ', ' : '';
                $message .= $comma.$key.' => from '.$this->before[$key].' to '.$this->after[$key];
                $change_item++;
            }
        }
        return $message." changed";
    }

    public function createLog()
    {
        return ModelLog::create([
            'model_name' => $this->model_name,
            'model_id' => $this->model_id,
            'user_id' => Auth::id(),
            'message' => $this->makeMessage(),
            'ip' => Request::ip(),
            'before' => $this->before,
            'after' => $this->after,
        ]);
    }
}
