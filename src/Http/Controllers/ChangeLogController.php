<?php
namespace Sdas\Changelog\Http\Controllers;

use App\Http\Controllers\Controller;
use Sdas\Changelog\Http\Models\ChangeLog;

class ChangeLogController extends Controller
{
    public function index() {
        $data = new ChangeLog();
        if(isset($_GET['term']) && $_GET['term'] != '') {
            $term = $_GET['term'];
            $data = $data->where(function($query) use ($term) {
                $query->orWhere('action_type', 'like', '%'.$term.'%');
                $query->orWhere('table_name', 'like', '%'.$term.'%');
                $query->orWhere('old_value', 'like', '%'.$term.'%');
                $query->orWhere('new_value', 'like', '%'.$term.'%');
            });
        }
        $data = $data->orderBy('id', 'desc')->paginate(5);

        // $data = ChangeLog::find(1);
        // echo '<pre>';
        // var_dump($data->old_value);
        // exit();

        return view('changelog::changelog.index',[
            'data' => $data
        ]);
    }
}