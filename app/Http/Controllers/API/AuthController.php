<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        
        //validation
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'image' => 'required',
            'shop_address' => 'required',
        ]);

        if( $validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,400);
        }

        $input = $request ->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['first_name'] = $user->first_name;
        $response = [
            'success'=> true,
            'data' =>$success,
            'message' =>'User register successfully'

        ];

        return response()->json($response);
    }


    public function login(Request $request){


        $validator = Validator::make($request->all(),[
            
            'phone_number' => 'required',
            'password' => 'required',
           
        ]);

        if( $validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response,400);
        }


        elseif(Auth::attempt(['phone_number'=> $request->phone_number,'password'=>$request->password])){

            $user = Auth::user();
           

            
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['first-name']= $user->first_name;

            $response= [
                'success'=> true,
                'data' => $success,
                'message' => 'User login Successfully'
            ];
            return response()->json($response);
        }
        

           else if (!Auth::attempt(['phone_number'=> $request->phone_number,'password'=>$request->password]))

        {
        

            $response2 = [
                'success' => false,
                'message' => 'UnAthorized'
            ];
            return response()->json($response2,401);
        }

    
    }



}
