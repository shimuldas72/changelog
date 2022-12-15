<?php
namespace Sdas\Changelog\Http\Traits;
use Sdas\Changelog\Http\Models\ChangeLog;

trait Trackable
{
    public static function bootTrackable()
    {
        static::creating(function ($model) {
            
        });

        static::created(function($model) {
            try{
                $primary_key = $model->getKeyName();
                $attributes = $model->getAttributes();

                $log = new ChangeLog();
                $log->action_type = 'create';
                $log->table_name = $model->getTable();
                $log->table_pk = $primary_key;
                $log->table_pk_value = (isset($attributes[$primary_key]))?$attributes[$primary_key]:null;
                $log->old_value = null;
                $log->new_value = json_encode($attributes);

                $log = $model->loadRequestInfo($log);
                $log->save();
            } catch(\Throwable $t){
                echo '<pre>';
                var_dump($t);
            }
        });

        static::updating(function ($model) {
            try{
                $changed_values = $model->getAttributes();
                $original_values = $model->getOriginal();
                $primary_key = $model->getKeyName();

                // echo '<pre>';
                // var_dump($changed_values);
                // exit();

                $compared_values = $model->getChangedValues($original_values, $changed_values);

                $log = new ChangeLog();
                $log->action_type = 'update';
                $log->table_name = $model->getTable();
                $log->table_pk = $primary_key;
                $log->table_pk_value = (isset($original_values[$primary_key]))?$original_values[$primary_key]:null;
                $log->old_value = json_encode($compared_values['old']);
                $log->new_value = json_encode($compared_values['new']);

                $log = $model->loadRequestInfo($log);
                $log->save();
            } catch(\Throwable $t){

            }
        });

        static::deleting(function ($model) {
            try{
                $attributes = $model->getAttributes();
                $primary_key = $model->getKeyName();

                $log = new ChangeLog();
                $log->action_type = 'delete';
                $log->table_name = $model->getTable();
                $log->table_pk = $primary_key;
                $log->table_pk_value = (isset($attributes[$primary_key]))?$attributes[$primary_key]:null;
                $log->old_value = json_encode($model->getAttributes());
                $log->new_value = null;

                $log = $model->loadRequestInfo($log);
                $log->save();
            } catch(\Throwable $t){

            }
        });
    }

    public function loadRequestInfo($model) {
        $request = request();

        if($request->route()) {
            $model->controller = $request->route()->getAction()['controller'];
            $model->route_name = $request->route()->getName();
        }

        $model->req_url = $request->fullUrl();
        $model->req_method = $request->method();
        $model->req_ip = $request->ip();
        $model->req_user_agent = $request->userAgent();

        return $model;
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