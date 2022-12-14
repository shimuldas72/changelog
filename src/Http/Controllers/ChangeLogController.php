<?php
namespace Sdas\Changelog\Http\Controllers;

use App\Http\Controllers\Controller;
use Sdas\Changelog\Http\Models\ChangeLog;
use Illuminate\Http\Request;

class ChangeLogController extends Controller
{
    public $changeLog;
     public function __construct(){
        $this->changeLog = new ChangeLog;
    }

    public function index() {

        return view('changelog::changelog.index');
    }

    public function ajaxList(Request $request) {
        try{
            $params = ['filters' => []];
            $logs = $this->changeLog->getLogList($request, $params);
            $items = $logs['items'];
            $data = array();

            if (!empty($items)) {
                $data = $this->changeLog->getDatatableData($items);
            }

            return $this->changeLog->getResponseForDatatable(intval($request->input('draw')),intval($logs['totalData']),$logs['totalFiltered'],$data);
        } catch (\Exception $exception){
            var_dump($exception->getMessage());
            exit();
            return $this->changeLog->getResponseForDatatable(0,0,0,[]);
        }
    }

    public function detail(Request $request, $id) {
        try{
            $data = ChangeLog::with('user')->find($id);

            $view = \Illuminate\Support\Facades\View::make('changelog::changelog.detail',
                    [
                        'item' => $data
                    ]);
            $contents = $view->render();

            $response = [
                'status' => 'success',
                'data' => $contents
            ];

            return response()->json($response);

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'data' => $exception->getMessage()
            ];

            return response()->json($response);
        }
    }
}