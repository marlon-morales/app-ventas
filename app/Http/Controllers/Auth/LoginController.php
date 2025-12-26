<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected function credentials(\Illuminate\Http\Request $request)
    {
        // Obtenemos email y password del formulario
        $credentials = $request->only($this->username(), 'password');

        // Le agregamos la condición: 'activo' debe ser true (1)
        $credentials['activo'] = true;

        return $credentials;
    }

    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        $user = \App\User::where($this->username(), $request->{$this->username()})->first();

        if ($user && !$user->activo) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => ['Tu cuenta ha sido desactivada. Contacta al administrador.'],
            ]);
        }

        throw \Illuminate\Validation\ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function loggedOut(\Illuminate\Http\Request $request)
    {
        // Redirige directamente a la ruta del login
        return redirect()->route('login');
    }
}
