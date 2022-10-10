<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobField;
use App\Models\Jobs;

class JobsController extends Controller
{
    public function __construct() {

        $this->middleware('auth');

    }

    public function index() {

        $joblist = Jobfield::orderBy('name', 'ASC')->get();

        $jobfield = Jobs::with('jobfield')->get();
        return view ('jobs.jobs', compact(['jobfield', 'joblist']));
    }

    public function getData(Request $request) {

        $id = $request->id;

        if($id) {
            $data = Jobs::where('id', $id)->first();
        } else {
            $data = Jobs::with('jobfield')->get();
            $no = 0;

            foreach($data as $d) {
                $d->no = $no+=1;
                $d->created_date = date_format($d->created_at, "d F Y - h:i");
            }

        }

    $result = [
        "data" => $data
    ];

    return response()->json($result);

    }

    public function createData(Request $request) {

        $result = [
            'status' => false,
            'data' => null,
            'message' => '',
            'newToken' => csrf_token()
        ];

        $data = new Jobs;
        $data->name = $request->name;
        $data->id_jobfield = $request->id_jobfield;
        $data->company = $request->company;
        $data->location = $request->location;
        $data->save();

        $result['newToken'] = csrf_token();
        $result['status'] = true;
        $result['data'] = $data;
        $result['message'] ="Success create new job";

        return response()->json($result);

    }

    public function deleteData($id) {
        $result = [
            'status' => false,
            'data' => null,
            'message' => '',
            'newToken' => csrf_token()
        ];

        $data = Jobs::where('id', $id)->first();

        $data->delete();

        $result['status'] = true;
        $result['message'] = "Job has been deleted";

        return response()->json($result);

    }

    public function updateData($id, Request $request) {

        $result = [
            'status' => false,
            'data' => null,
            'message' => '',
            'newToken' => csrf_token()
        ];

        $data = Jobs::where('name', $request->name)->first();

        $data->name = $request->name;
        $data->id_jobfield = $request->id_jobfield;
        $data->company = $request->company;
        $data->location = $request->location;
        $data->save();

        $result['status'] = true;
        $result['data'] = $data;
        $result['message'] = "Update Job successfuly";

        return response()->json($result);

    }

    public function trash() {

        $joblist = Jobfield::orderBy('name', 'ASC')->get();

        $jobfield = Jobs::with('jobfield')->get();
        return view ('jobs.trash', compact(['jobfield', 'joblist']));
    }

    public function restoreData($id) {

        $restore = Jobs::where('id', $id)->onlyTrashed()->restore();

        if ($restore) {
            $result = [
                'status' => true,
                'message' => 'success restore data',
                'data' => $restore
            ];

            return response()->json($result);
        }

    }

    public function deletePermanentData($id) {

        $result = [
            'status' => false,
            'message' => '',
            'data' => null,
            'newToken' => csrf_token()
        ];

        $data = Jobs::where('id', $id)->onlyTrashed()->first();

        if(!$data) {
            $result['message'] = 'Data not found';
            return response()->json($result);
        }

        $data->forceDelete();

        $result['status'] = true;
        $result['message'] = 'Delete has been deleted permanently';
        $result['data'] = $data;

        return response()->json($result);
    }

    public function getDataTrash(Request $request) {

        if($request->id) {
            $data = Jobs::where('id', $request->id)->first();
        } else {
            $no = 0;
            $data = Jobs::with('jobfield')->onlyTrashed()->get();

            foreach ($data as $d) {
                $d->no = $no+=1;
                $d->created_date = date_format($d->created_at, "d F Y - h:i");
            }
        }

        $result['data'] = $data;

        return response()->json($result);

    }

}
