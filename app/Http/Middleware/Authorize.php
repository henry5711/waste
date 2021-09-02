<?php


namespace App\Http\Middleware;


use Closure;
use Firebase\JWT\JWT;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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
            return response()->json(["error"=>true,"message"=>'unauthorized'],403);
        }

        $array_map = [];
        foreach ($user->roles as $key => $item) {
            $array_map[$key] = strtolower($item);
        }
        if(count(array_intersect(["admin","sysadmin","superadmin"], $array_map)) == 0) {
            return response()->json(["error"=>true,"message"=>'unauthorized'],403);
        }

        return $next($request);
    }
}