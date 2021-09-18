<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
    use Illuminate\Support\Facades\Auth;
    use Cache;
    use Carbon\Carbon;
    use App\User;
    class JwtMiddleware extends BaseMiddleware
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
            try {
                $user = JWTAuth::parseToken()->authenticate();
                $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
                Cache::put('active-' . Auth::user()->id, true, $expiresAt);
                // last seen
                User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
                
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['status' => 'Token is Invalid']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['status' => 'Token is Expired']);
                }else{
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }
            return $next($request);
        }
    }