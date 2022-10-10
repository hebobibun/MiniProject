<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Redirect;

use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    public function index() {

        if(Auth::user()){
            return redirect('/dashboard');
        }

        return view('login');
    }

    public function login(Request $request) {

        $user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if($user) {
            return redirect()->to('/dashboard');
        } else {
            session()->flash('message', 'User not found');
            return Redirect::back();
        }

    }

        public function register() {
        return view('register');
    }

    public function storeRegist(Request $request){

        $result = [
            'data' => null,
            'status' => false,
            'newToken'=>csrf_token(),
            'message'=>''
        ];

        if($request->newpassword != $request->repassword){
            $result['message'] = "Password anda berbeda !!";
            return response()->json($result);
        }

        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $password = Hash::make($request->newpassword);
        $data->password = $password;
        $data->save();
        $result['data'] = $data;
        $result['status'] = true;
        $result['message'] = "Registrasi berhasil!!";

        return response()->json($result);
    }
}
