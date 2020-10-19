<?php

namespace App\Controllers\Auth;

use App\Models\Auth\Reg;
use App\Components\Guard;
use App\Requests\LoginRequest;
use App\Controllers\ApiResponser;
use System\Foundation\Controller;

class LoginController extends Controller
{
    use ApiResponser;

    /**
     * Страница входа
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Аутентификация
     *
     * @param LoginRequest $request
     *
     * @return \System\Http\Response
     */
    public function auth(LoginRequest $request)
    {
        if (!$user = Reg::getUserForAuth($request->post('login'))) {
            return $this->error(['login' => 'Пользователя с таким Login не зарегистрирован']);
        }

        if ($user['password'] !== md5($request->post('password'))) {
            return $this->error(['password' => 'Пароль неверный']);
        }

        Guard::login($user);

        return $this->success($user);
    }

    public function quit()
    {
        Guard::logout();

        return redirect('/');
    }
}
