<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(ResponseService $responseService){
        $this->responseService = $responseService;
    }

    public function Register(Request $request)
    {
        $input          = $request->all();
        $validator      = Validator::make($input,[
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required',
            'password2' => 'required|same:password',
        ]);
        
        if ($validator->fails()) {
            return $this->responseService->errorResponse('Validator Error',$validator->errors());;
        }

        $input['password']  = bcrypt($input['password']);
        $user               = User::create($input);

        $response = [
            'token' => $user->createToken('Monzatech')->plainTextToken,
            'name'  => $user->name,
            'email' => $user->email
        ];

        return $this->responseService->successResponse($response, 'User Successfully Registered');
    }
    
    public function login(Request $request)
    {
        $input          = $request->all();
        $validator      = Validator::make($input,[
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        
        if ($validator->fails()) {
            return $this->responseService->errorResponse('Validator Error',$validator->errors());;
        }

        if (Auth::attempt(['email' => $request->email,'password' => $request->password]))
        {
            $user = Auth::user();
            $response = [
                'token' => $user->createToken('Monzatech')->plainTextToken,
                'name'  => $user->name,
                'email' => $user->email
            ];
            
            return $this->responseService->successResponse($response, 'User Successfully Login');
        }else{
            return $this->responseService->errorResponse('Your email or password is not valid');
        }
    }
}

