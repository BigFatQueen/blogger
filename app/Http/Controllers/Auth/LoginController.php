<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:8|max:50',
            'password' => 'required'
        ]);

        $remember = $request->has('remember') ? true : false;

        $check = $request->only('email', 'password');
        
        if(Auth::attempt($check, $remember))
        {
            return redirect(RouteServiceProvider::HOME);
        }else
        {
            return back()->with('status','These credentials do not match our records.');
        }
    }
    public function authenticated($request,$user){
        $previous = $request->session()->get('previous_url');
        $previous_reg = $request->session()->get('previous_url_reg');
        if ($previous_reg) {
            $previous = $previous_reg;
        }else {
            $previous = $previous;
        }
        return redirect(RouteServiceProvider::HOME);
    }
}
