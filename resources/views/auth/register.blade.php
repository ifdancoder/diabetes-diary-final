@extends('layouts.app')

@section('content')
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="card card-md">
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark"><img src="{{ asset('assets') }}/dist/img/logo/blood.png" class="h-8" alt=""></a>
                </div>
                <form class="mt-0" method="POST" action="{{ route('register.send') }}" autocomplete="on" novalidate>
                    @csrf
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Зарегистрироваться</h2>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <label class="form-label">Имя</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" placeholder="Иван" autocomplete="off" name="first_name" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Фамилия</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" placeholder="Иванов" autocomplete="off" name="last_name" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Электронная почта</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="your_email@gmail.com" autocomplete="off" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
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
                            <div class="col-6">
                                <label class="form-label">
                                    Повторите пароль
                                </label>
                                <input type="password" class="form-control  @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="current-password" placeholder="Повторите пароль" autocomplete="off">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" type="checkbox" name="remember_me" id="remember_me" {{ old('remember_me') ? 'checked' : '' }} />
                                <span class="form-check-label">Запомнить на этом устройстве</span>
                            </label>
                        </div>
                        <div class="mb-1">
                            <button type="submit" class="btn btn-outline-primary w-100">Зарегистрироваться</button>
                        </div>
                    </div>
                </form>
                <div class="mb-3 d-flex justify-content-center">
                    <div class="d-flex align-items-center">
                        <div class="text-center text-muted">
                            Уже есть аккаунт?
                        </div>
                        <a href="{{ route('login') }}" class="ml-2">&nbspВойти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection