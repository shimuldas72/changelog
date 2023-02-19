<?php

namespace Sdas\Changelog\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\User;

class ChangeLog extends Model
{

  	protected $table = 'change_log';
  	public $timestamps=false;
  	protected $fillable = [
  		'action_type',
	    'table_name',
      'table_pk',
      'table_pk_value',
	    'old_value',
	    'new_value',
      'controller',
      'route_name',
      'req_url',
      'req_method',
      'req_ip',
      'req_user_agent',
	    'created_by'
  	];

    // TODO :: boot
   	// boot() function used to insert logged user_id at 'created_by' & 'updated_by'
   	public static function boot(){
       	parent::boot();
       	static::creating(function($query){
           	if(Auth::check()){
               	$query->created_by = @\Auth::user()->id;
           	}
       	});
   	}

	public function getOldValueAttribute($value) {
		return (array)json_decode($value);
	}

	public function getNewValueAttribute($value) {
		return (array)json_decode($value);
	}
  public function user() {
      return $this->hasOne(Config::get('changelog')['userClass'], 'id', 'created_by');
  }

  public function getResponseForDatatable($draw,$recordsTotal,$recordFiltered,$data){
      $json_data = array(
          "draw" => $draw,
          "recordsTotal" => $recordsTotal,
          "recordsFiltered" => $recordFiltered,
          "data" => $data,
      );
      return json_encode($json_data);
  }

  public function getLogList(
      $request=[],
      $params=[],
      $columns = ['change_log.*'],
      $withTrashed = false,
      $onlyDeleted = false
  )
  {
      $classs = Config::get('changelog')['userClass'];
      $name_column = Config::get('changelog')['name_column'];
      $user_class = new $classs;
      $tbl_name = $user_class->getTable();

      if ($request->input('order')) {
          $col = $request->input('order.0.column');
          $order = $request->input('columns.' . (int)$col . '.data');
          $order = 'change_log.'.$order;
          $dir = $request->input('order.0.dir');
      } else {
          $order = 'change_log.id';
          $dir = 'desc';
      }

      $query = self::leftJoin($tbl_name,$tbl_name.'.id','=',$this->table.'.created_by')->select($columns)->orderBy($order, $dir);
      $totalData = $query->count();

      if ($request->search && $request->search['value'] != "") {
          $keyword = $request->search['value'];

          foreach ($request->input('columns') as $val) {
              if ($val['searchable'] == "false") continue;
              if(in_array($val['data'], ['id', 'created_at'])) {
                $val['data'] = 'change_log.'.$val['data'];
              }

              if($val['name'] == 'created_by') {

                $query->orWhereRaw('lower(' . $name_column . ') like (?)', '%' . strtolower($keyword) . '%');
              } else {
                $query->orWhereRaw('lower(' . $val['data'] . ') like (?)', '%' . strtolower($keyword) . '%');
              }
          }
      }
      // var_dump($query->toSql());
      // exit();

      //if(!empty($params)) $query = $query->ofFilter(isset($params['filters']) ? $params['filters'] : null);

      $totalFiltered = $query->count();

      $limit = $request->input('length');
      $start = $request->input('start');

      if ($limit == -1) {
          $items = $query->get();
      } else {
          $items = $query->when($limit, function($q) use ($start, $limit) {
              $q->offset($start)->limit($limit);
          })->get();
      }

      return [
          'items'=>$items,
          'totalData'=>$totalData,
          'totalFiltered'=>$totalFiltered
      ];
  }

  public function getDatatableData($items, $data = array()) {
      try {
          foreach ($items as $key => $item) {
            $user_name_column = Config::get('changelog')['name_column'];

            // $old_values = \Illuminate\Support\Facades\View::make('changelog::changelog._old_value', [ 'item' => $item ])->render();
            // //$old_values = $view->render();
            //$new_values = \Illuminate\Support\Facades\View::make('changelog::changelog._new_value', [ 'item' => $item ])->render();
            $old_values = self::getTableOldNewValue($item->old_value);
            $new_values = self::getTableOldNewValue($item->new_value);


            $nestedData['key'] = $key + 1;
            $nestedData['id'] = $item->id;
            $nestedData['action_type'] = $item->action_type;
            $nestedData['table_name'] = $item->table_name;
            if($item->table_pk) {
              $nestedData['table_name'] .= '<br/><a class="btn btn-sm btn-primary show_timeline" data-href="'.route('changelog.timeline', $item->id).'">Timeline of '.strtoupper($item->table_pk).' -'.$item->table_pk_value.'</a>';
            }

            $nestedData['old_value'] = $old_values;
            $nestedData['new_value'] = $new_values;

            $nestedData['tracking'] = '<strong>Url</strong>: ('.$item->req_method.') '.$item->req_url;

            $nestedData['controller'] = $item->controller.'<br>'.$item->route_name;
            $nestedData['route_name'] = $item->route_name;
            $nestedData['req_url'] = $item->req_url.'<br/>'.$item->req_method;
            $nestedData['req_method'] = $item->req_method;
            $nestedData['created_by'] = ($item->user)?$item->user->$user_name_column.' ('.$item->created_by.')':$item->created_by;
            $nestedData['created_at'] = date('d-M-y h:i:s A', strtotime($item->created_at));


            $nestedData['tracking'] .= '<br/><strong>Created By: </strong>'.$nestedData['created_by'];
            $nestedData['tracking'] .= '<br/><strong>Created At: </strong>'.$nestedData['created_at'];

            $nestedData['action'] = '';
            $nestedData['action'] .= '<a title="View" href="" data-href="'.route('changelog.detail', $item->id).'" class="btn-sm btn btn-info btn-detail show_detail" style="display: inline-block !important">Details</a>';

            $data[] = $nestedData;
          }

          return $data;
      } catch (\Exception $exception) {
          var_dump($exception->getMessage());
      }
  }

  public static function getTableOldNewValue($value) {

    if($value){
      
      $str = '';
      $i = 0;
      if(count($value) > 0) {
        foreach ($value as $akey => $aval) {
          $i++;

          if($i > 1){
            $str .= '<br/>';
          }
          $str .= '<strong>'.$akey.'</strong>'.': '.$aval;
        }
      } 
    
      return '<div style="max-width: 50vw; overflow: auto; background: #fff; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;">'.$str.'</div>';
    }
      
    return '';
  }


}
