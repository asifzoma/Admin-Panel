<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log an activity.
     *
     * @param  string  $action
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected function logActivity(string $action, Model $model)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'description' => $this->generateLogDescription($action, $model),
        ]);
    }

    /**
     * Generate a description for the log.
     *
     * @param  string  $action
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    private function generateLogDescription(string $action, Model $model): string
    {
        $modelName = strtolower(class_basename($model));

        return ucfirst($action)." {$modelName} #".$model->id;
    }
}
