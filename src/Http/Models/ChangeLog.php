<?php

namespace Sdas\Changelog\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class ChangeLog extends Model
{

  	protected $table = 'change_log';
  	public $timestamps=false;
  	protected $fillable = [
  		'action_type',
	    'table_name',
	    'old_value',
	    'new_value',
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
      return $this->hasOne(User::class, 'id', 'created_by');
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
      $columns = ['*'],
      $withTrashed = false,
      $onlyDeleted = false
  )
  {

      if ($request->input('order')) {
          $col = $request->input('order.0.column');
          $order = $request->input('columns.' . (int)$col . '.data');
          $dir = $request->input('order.0.dir');
      } else {
          $order = 'id';
          $dir = 'desc';
      }

      $query = self::select($columns)->orderBy($order, $dir);
      $totalData = $query->count();

      if ($request->search && $request->search['value'] != "") {
          $keyword = $request->search['value'];

          foreach ($request->input('columns') as $val) {
              if ($val['searchable'] == "false") continue;
              $query->orWhereRaw('lower(' . $val['data'] . ') like (?)', '%' . strtolower($keyword) . '%');
          }
      }
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

            $old_values = \Illuminate\Support\Facades\View::make('changelog::changelog._old_value', [ 'item' => $item ])->render();
            //$old_values = $view->render();
            $new_values = \Illuminate\Support\Facades\View::make('changelog::changelog._new_value', [ 'item' => $item ])->render();


            $nestedData['key'] = $key + 1;
            $nestedData['id'] = $item->id;
            $nestedData['action_type'] = $item->action_type;
            $nestedData['table_name'] = $item->table_name;
            $nestedData['old_value'] = $old_values;
            $nestedData['new_value'] = $new_values;
            $nestedData['created_by'] = ($item->user)?$item->user->name.' ('.$item->created_by.')':$item->created_by;
            $nestedData['created_at'] = date('d-M-y h:i:s A', strtotime($item->created_at));

            $nestedData['action'] = '';
            $nestedData['action'] .= '<a title="View" href="" data-href="'.route('changelog.detail', $item->id).'" class="btn-sm btn btn-info btn-detail show_detail" style="display: inline-block !important">Details</a>';

            $data[] = $nestedData;
          }

          return $data;
      } catch (\Exception $exception) {
          var_dump($exception->getMessage());
      }
  }
}
