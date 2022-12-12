<?php
namespace Sdas\Changelog\Http\Traits;
use Sdas\Changelog\Http\Models\ChangeLog;

trait Trackable
{
    public static function bootTrackable()
    {
        static::creating(function ($model) {
            $log = new ChangeLog();
            $log->action_type = 'create';
            $log->table_name = $model->getTable();
            $log->old_value = null;
            $log->new_value = json_encode($model->getAttributes());
            $log->save();
        });

        static::updating(function ($model) {
            $changed_values = $model->getDirty();
            $original_values = $model->getOriginal();

            $log = new ChangeLog();
            $log->action_type = 'update';
            $log->table_name = $model->getTable();
            $log->old_value = json_encode($model->getPreviousValues($original_values, $changed_values));
            $log->new_value = json_encode($changed_values);
            $log->save();
        });

        static::deleting(function ($model) {
            $log = new ChangeLog();
            $log->action_type = 'delete';
            $log->table_name = $model->getTable();
            $log->old_value = json_encode($model->getAttributes());
            $log->new_value = null;
            $log->save();
        });
    }

    public function getPreviousValues($original_values, $changed_values)
    {
        $sortedChanges = [];

        if(count($changed_values) > 0) {
            foreach ($changed_values as $key => $value) {
                if(isset($original_values[$key])) {
                    $sortedChanges[$key] = $original_values[$key];
                }
            }
        }

        return $sortedChanges;
    }
}