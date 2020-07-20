<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class AuthController extends Controller
{
    public $successStatus = 200;

   
    public function index(){
        $user_data = User::all();
        return view('index', compact('user_data'));

    }
    public function register(Request $request){

          $validator = Validator::make($request->all(), [ 
                   'name'=> 'required',
                   'email' => 'required|email',
                    'mobile' => 'required',
                    'dob' => 'required',
                   'gender'=> 'required',
                   'password' => 'required',
                   'c_password' => 'required|same:password'
            ]);

            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
       
            $input = $request->all(); 
            $input['password'] = bcrypt($input['password']); 
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
            return response()->json(['success'=>$success], $this-> successStatus); 


    }

    public function login(Request $request){ 
     
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function details() 
    { 
       // $user = Auth::user(); 
        $user = user::get()->all();
       // \Hash::check($token,$user->api_token) ? $user : null;
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

}
