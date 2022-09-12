<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    //login api
    public function login(Request $request){
        //If check success then return results success
        if (Auth::guard('user')->attempt([
            'phone' => $request->params["phone"],
            'password' => $request->params["password"]
            ])){
            //const 
            $message = "Đăng nhập thành công";
            $token = Str::random(length:60);
            $refresh_token = Str::random(length:60);
            
            //update token, refresh_token, status
            DB::table('users')
            ->where('phone',$request->params["phone"])
            ->update([
                'token' =>   $token,
                'refresh_token' =>  $refresh_token,
            ]);

            //results
            $result = [
                "code" => 200,
                "message" => $message,
                "validate_token" => true,
                "data" => [
                    "token" => $token,
                    "refresh_token" => $refresh_token,
                ],
            ];
            return  response()->json([
               "id" => null,
               "results"=> $result,
            ],200);
        }

        //If check Phone failed then return result failed
        $isPhone = DB::table('users')
        ->where('phone',$request->params["phone"])
        ->exists();
        if(!$isPhone && !empty($request->params["password"]) ){
            $messagePhoneFailed = "Tài khoản không tồn tại!";
            //return messgae
            $result = [
                "code" => 201,
                "message" => $messagePhoneFailed,
                "validate_token" => false,
                "data" => [],
            ];
            return  response()->json([
                "id" => null,
                "results"=> $result,
            ],200);

        }else if($isPhone && !empty($request->params["password"]) ){

            //If check password failed then return result failed
            $messagePasswordFailed = "Mật khẩu không đúng!";
            $result = [
                "code" => 201,
                "message" => $messagePasswordFailed,
                "validate_token" => false,
                "data" => [],
            ];
            return  response()->json([
                "id" => null,
                "results"=> $result,
            ],200);
        }


        //If data empty return failed params
        $result = [
            "code" => 404,
            "message" => "Tham số không được Null",
            "validate_token" => true,
            "data" => [],
        ];
        return  response()->json([
            "id" => null,
            "results"=> $result,
        ],200);
    }
    
    //change password for user
    public function changePassword(Request $request){
        // IF Token is null return reponse error server
      
        if(empty($request->params["token"]) || empty($request->params["new_password"]) || empty($request->params["old_password"])){
            //token or newpassword, oldpassword is null
            $result = [
                "code" => 404,
                "message" => "Params invalid!",
                "data" => [],
            ];
            return  response()->json([
                "id" => null,
                "results"=> $result,
            ],200);
        }else{
            //check token exist
            $isToken = DB::table('users')->where('token',$request->params["token"])->exists();

            if($isToken){
                //change password for User
                $user=  DB::table('users')
                    ->where('token',$request->params["token"])
                    ->select('password')->get();
                  //If current password different new passowrd then return messages
                if(Hash::check($request->params["old_password"], $user[0]->password)){

                    //update password
                    DB::table('users')
                    ->where('token',$request->params["token"])
                    ->update([
                        'password' => Hash::make($request->params["new_password"])
                    ]);

                    $message = "Thay đổi mật khẩu thành công!";
                    
                    $result = [
                        "code" => 200,
                        "message" => $message,
                        "data" => [],
                    ];
                    return  response()->json([
                        "id" => null,
                        "results"=> $result,
                    ],200);

                }else{
                    //Check password current failed
                    $message = "Mật khẩu hiện tại của bạn không đúng!";
                    
                    $result = [
                        "code" => 201,
                        "message" => $message,
                        "data" => [],
                    ];
                    return  response()->json([
                        "id" => null,
                        "results"=> $result,
                    ],200);
                }
            }
        }

        //failed
        $result = [
            "code" => 404,
            "message" => "Server failed",
            "data" => [],
        ];
        return  response()->json([
            "id" => null,
            "results"=> $result,
        ],200);
    }

    public function getInformationUser(Request $request){
        if(empty($request->params["token"])){
            //token is null
            $result = [
                "code" => 404,
                "message" => "Params token invalid!",
                "data" => [],
            ];
            return  response()->json([
                "id" => null,
                "results"=> $result,
            ],200);
        }

        $user = DB::table('users')->get();
        $gender_type = "Another";
        //check gender
        if($user[0]->gender == 1){
            $gender_type = "Male";

        }else if($user[0]->gender == 2){
            $gender_type = "Female";
        }

        $result = [
            "code" => 200,
            "message" => "Success!",
            "data" => [
                "user_id" => $user[0]->id,
                "phone" => $user[0]->phone,
                "fullname" => $user[0]->fullname,
                "full_address" => $user[0]->address_name,
                "gender" => $gender_type,
                "avatar_url" => $user[0]->avatar,
           
            ],
        ];
        return  response()->json([
            "id" => null,
            "results"=> $result,
        ],200);
    }

    //put new password for user when they are forgot current password
    public function resetPassword(Request $request){
        
    }
}
