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
        $keys = array_keys($this->before);
        for ($i = 0; $i < count($keys); $i++) {
            if (isset($this->before[$keys[$i]]) && $this->after[$keys[$i]] && $this->before[$keys[$i]] != $this->after[$keys[$i]]) {
                $message .= ", ".$keys[$i]." => ".$this->before[$keys[$i]]. ' dan '. $this->after[$keys[$i]]. " ga ";
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
