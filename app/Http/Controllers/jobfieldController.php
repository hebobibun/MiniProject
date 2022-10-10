<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobField;


class jobfieldController extends Controller
{
    public function indexJobfield() {
        return view ('jobs.jobfield');
    }

    public function getData(Request $request) {

        $id = $request->id;

        if($id) {
            $data = JobField::where('id', $id)->first();
        } else {
            $data = JobField::get();
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

        $data = new JobField;
        $data->name = $request->name;
        $data->description = $request->description;
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

        $data = JobField::where('id', $id)->first();

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

        $data = JobField::where('id', $id)->first();

        $data->name = $request->name;
        $data->description = $request->description;
        $data->save();

        $result['status'] = true;
        $result['data'] = $data;
        $result['message'] = "Job Field Updated Succesfully";

        return response()->json($result);

    }
}
