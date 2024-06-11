@extends('layouts.app')

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="card card-md">
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark"><img src="{{ asset('assets') }}/dist/img/logo/blood.png" class="h-8" alt=""></a>
                </div>
                <form class="mt-0" method="POST" action="{{ route('login.send') }}" autocomplete="on" novalidate>
                    @csrf
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Войдите</h2>
                        <div class="mb-2">
                            <label class="form-label">Электронная почта</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="your_email@gmail.com" autocomplete="off" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                Пароль
                            </label>
                            <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Введите пароль" autocomplete="off">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" type="checkbox" name="remember_me" id="remember_me" {{ old('remember') ? 'checked' : '' }} />
                                <span class="form-check-label">Запомнить на этом устройстве</span>
                            </label>
                        </div>
                        <div class="mb-1">
                            <button type="submit" class="btn btn-outline-primary w-100">Войти</button>
                        </div>
                    </div>
                </form>
                <div class="mb-3 d-flex justify-content-center">
                    <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="diabetes_diary_bot" data-size="large" data-userpic="false" data-auth-url="{{ route('tg.login') }}" data-request-access="write"></script>
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <div class="d-flex align-items-center">
                        <div class="text-center text-muted">
                            Еще нет аккаунта?
                        </div>
                        <a href="{{ route('register') }}" class="ml-2">&nbspЗарегистрироваться</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection