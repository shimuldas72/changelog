<?php
namespace Sdas\Changelog\Http\Traits;
use Sdas\Changelog\Http\Models\ChangeLog;

trait Trackable
{
    public static function bootTrackable()
    {
        static::creating(function ($model) {
            try{
                $log = new ChangeLog();
                $log->action_type = 'create';
                $log->table_name = $model->getTable();
                $log->old_value = null;
                $log->new_value = json_encode($model->getAttributes());
                $log->save();
            } catch(\Throwable $t){

            }
        });

        static::updating(function ($model) {
            try{
                $changed_values = $model->getDirty();
                $original_values = $model->getOriginal();

                $compared_values = $model->getChangedValues($original_values, $changed_values);

                $log = new ChangeLog();
                $log->action_type = 'update';
                $log->table_name = $model->getTable();
                $log->old_value = json_encode($compared_values['old']);
                $log->new_value = json_encode($compared_values['new']);
                $log->save();
            } catch(\Throwable $t){

            }
        });

        static::deleting(function ($model) {
            try{
                $log = new ChangeLog();
                $log->action_type = 'delete';
                $log->table_name = $model->getTable();
                $log->old_value = json_encode($model->getAttributes());
                $log->new_value = null;
                $log->save();
            } catch(\Throwable $t){

            }
        });
    }

    public function getChangedValues($original_values, $changed_values)
    {
        $old = [];
        $new = [];

        if(count($changed_values) > 0) {
            foreach ($changed_values as $key => $value) {
                if(isset($original_values[$key])) {
                    
                    if($original_values[$key] !== $value) {
                        $old[$key] = $original_values[$key];
                        $new[$key] = $changed_values[$key];
                    }

                }
            }
        }

        return [
            'old' => $old,
            'new' => $new
        ];
    }
}