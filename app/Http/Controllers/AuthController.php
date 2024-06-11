<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\TelegramAuthTrait;
use App\Models\User;
use App\Models\PersonalSettings;

use App\Notifications\LoggedIn;
use App\Notifications\LoggedOut;
use App\Notifications\Registered;

class AuthController extends Controller
{
    use TelegramAuthTrait;

    public function loginGet(Request $request)
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Ввод электронной почты обязателен',
            'email.email' => 'Введите действительный адрес электронной почты',
            'password.required' => 'Ввод пароля обязателен'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        $credentials = $request->only('email', 'password');

        $auth_result = auth()->attempt($credentials, $remember_me);

        if ($auth_result) {
            $user = auth()->user();

            $user->notify(new LoggedIn());

            return redirect()->route('home');
        }
        else {
            return redirect()->route('login')->with('danger', 'Неверный логин или пароль');
        }
    }

    public function registerGet(Request $request)
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
        ], [
            'first_name.max' => 'Имя должно содержать не более 255 символов',
            'last_name.required' => 'Фамилия должна содержать не более 255 символов',
            'email.required' => 'Ввод электронной почты обязателен',
            'email.email' => 'Введите действительный адрес электронной почты',
            'email.unique' => 'Этот адрес электронной почты уже зарегистрирован',
            'email.max' => 'Электронная почта должна содержать не более 255 символов',
            'password.required' => 'Ввод пароля обязателен',
            'password.min' => 'Пароль должен содержать не менее 6 символов',
            'password_confirmation.required' => 'Подтверждение пароля обязательно',
            'password_confirmation.min' => 'Пароль должен содержать не менее 6 символов',
            'password_confirmation.same' => 'Пароли не совпадают',
        ]);
        
        $remember_me = $request->has('remember_me') ? true : false;

        $user = User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                ]);

        PersonalSettings::create([
            'user_id' => $user->id
        ]);

        auth()->login($user, $remember_me);
        
        $user->notify(new Registered());

        return redirect()->route('home');
    }

    public function logoutPost()
    {   
        $user = auth()->user();

        $user->notify(new LoggedOut());
        
        auth()->logout();
        
        return redirect()->route('login');
    }

    public function TGLogin(Request $request)
    {
        $proved = $this->telegramAuth($request);
        if ($proved) {
            $user = User::where('tg_id', $proved['id'])->first();
            
            if ($user == null) {
                return redirect()->route('login')->with('danger', 'Не найден профиль, к которому привязан этот Telegram аккаунт');
            }

            $user->notify(new LoggedIn());
            auth()->login($user, true);

            return redirect()->route('home')->with('success', 'Вы успешно аутентифицировались посредством Telegram');
        }
    }

    public function TGConnect(Request $request)
    {
        $proved = $this->telegramAuth($request);
        if ($proved) {
            $user = auth()->user();

            $user->tg_id = $proved['id'];
            $user->save();

            return redirect()->route('profile', ['current_tab' => 'social'])->with('success', 'Telegram аккаунт успешно подключен');
        }
        return redirect()->route('home')->with('danger', 'Не удалось подключиться к Telegram');
    }
}
