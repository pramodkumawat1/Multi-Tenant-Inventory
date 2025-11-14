<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseBuilder;
use App\Http\Resources\UserResource;
use App\Models\User;
use Validator;
use Auth;
use DB;

class AuthController extends Controller
{
    /**
     * User Login Function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        try {
            // Validation start
            $validSet = [
                'email' => 'required',
                'password' => 'required',
            ]; 

            $validator = Validator::make($request->all(), $validSet );

            if($validator->fails()) {
                return ResponseBuilder::error($validator->errors()->first(), 422);
            }
            // Validation end

           $user = User::getUserByEmail($request->email);

            if(!$user) {
                return ResponseBuilder::error(trans('global.not_registered'), 404);
            }

            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return ResponseBuilder::error(trans('global.record_not_found'), 404);
            }

            $token = auth()->user()->createToken('API Token')->accessToken;
            $this->response = new UserResource($user);

            return ResponseBuilder::successwithToken($token, $this->response, trans('global.login_success'), 200);
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.something_wrong'),$this->badRequest);
        }
    }
}
