<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\User;
use DB;
use App\MiddlewareClient;

class CASAuth
{

    protected $auth;
    protected $cas;
    protected $user;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->cas = app('cas');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if( $this->cas->isAuthenticated() )
        {
            // Store the user credentials in a Laravel managed session
            $casUser = $this->cas->user();
            if (!session()->has('cas_user') || $this->auth->guest() || $this->auth->user()->username != $casUser){
                
                $response = MiddlewareClient::getUserProfile($casUser);

                $user = User::where('username', $casUser)->first();
                //dd($response);
		if ($response['access_portal']){
                   if (is_null($user)){
                       $is_external = $response['data']['kategori_user_id'] != env('MW_INTERNAL_USER_CATEGORY_ID', 1);
                       $sdm_user = User::create([
                           'username' => $response['data']['username'],
                           'email' => $response['data']['email'],
                           'is_external' => $is_external,
                           'name' => $is_external ? $response['data']['bumn_singkat'] : $response['data']['name'],
                       ]);
                       $sdm_user->save();
                       //$fis_user->roles()->attach(env('PREPARE_ROLE_ID', null));
                   }

                    $user = User::where('username', $casUser)->first();
		    
                    $this->auth->login($user);
                    session()->put('cas_user', $casUser);
                } else {

                    if ($request->ajax()) {
                        return response('Unauthorized.', 401);
                    }
                    $this->auth->logout();
                    \Cas::logoutWithRedirectService(env('CAS_LOGOUT_REDIRECT_URL', ''));
                }
            }
        } else {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }
            \Auth::logout();
            $this->cas->authenticate();
        }


        

        return $next($request);
    }
}
