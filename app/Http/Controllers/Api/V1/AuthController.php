<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\SimpleUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function check(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [], [
            'email' => 'ایمیل',
            'password' => 'رمز عبور'
        ]);

        if ($validator->fails()) {
            return response([
                'message' =>  $validator->errors()->first(),
                'type' => 'danger'
            ], 422);
        }

      
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = Str::random(100);
                $response = ['token' => $token];
                if (!$user->is_active) {
                    return response([
                        'message' =>  'حساب کاربری شما غیرفعال می باشد، لطفا با پشتیبانی تماس بگیرید.',
                        'type' => 'danger'
                    ], 422);
                }
                $user->update([
                        'api_token' => $token,
                    ]);
            
                return response([
                    'user' => new SimpleUserResource($user)
                    , 200]);
            } else {
                $response = ["message" => "رمز عبور صحیح نیست"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }

        // if (!$user->is_active) {
        //     return response([
        //         'message' =>  'حساب کاربری شما غیرفعال می باشد، لطفا با پشتیبانی تماس بگیرید.',
        //         'type' => 'danger'
        //     ], 422);
        // }
        // $user->update([
        //     'api_token' => Str::random(100),
        // ]);


        // return response([
        //     'user' => new SimpleUserResource($user)
        // ]);
    }
}
