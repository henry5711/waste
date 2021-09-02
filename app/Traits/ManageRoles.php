<?php


namespace App\Traits;


use Firebase\JWT\JWT;
use Illuminate\Http\Request;

trait ManageRoles
{
    /**
     * @param Request $request
     * @param mixed ...$roles
     * @return bool
     */
    public function haveRoles(Request $request, ...$roles){

        if($request->hasHeader("Authorization")) {
            $token = $request->header("Authorization");
        }
        if($request->has("token")){
            $token=$request->input('token');
        }
        $tks = explode('.', $token);
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $user = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        if(!$user OR !isset($user->roles) OR empty($user->roles)){
            return false;
        }

        $array_map = [];
        foreach ($user->roles as $key => $item) {
            $array_map[$key] = strtolower($item);
        }
        if(count(array_intersect($roles, $array_map)) == 0) {
            return false;
        }

        return true;

    }
}