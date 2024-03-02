<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;

class ClerkAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');
        $publicKey = file_get_contents('/var/www/mopao/newday/storage/app/public/p.pem');

        $authorization = explode(" ", $authorizationHeader);
        if (count($authorization) === 2) {
            try {
                $token = $authorization[1];
                
                $tokenPayload = JWT::decode($token, new Key($publicKey, 'RS256'));
            
                // Custom logic to find user from database using token payload
                $user = User::where('authProviderId', $tokenPayload->sub)->first();

                if (!empty($user)) {
                    // Authenticate user
                    auth()->login($user);
                    return $next($request); // Return the ID of the authenticated user
                } else {
                    abort(401);
                }
            } catch (ExpiredException $e) {
                abort(401);
            } catch (BeforeValidException $e) {
                abort(401);
            } catch (\Throwable $e) { // Catch any other exceptions
                abort(401);
            }
        } 

        abort(401);
    }
}
