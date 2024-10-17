@extends('layouts.main')

@section('content')

    @include('layouts.includes.menu')

    <section class="vh-100">
        <div class="container py-5 h-85">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
{{--                                <img src="img/photo.jpg"--}}
{{--                                     alt="login form" class="img-fluid"--}}
{{--                                     style="width: 490px; height: 760px; border-radius: 10px"/>--}}
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form method="POST" action="{{ route('registration_process') }}">
                                        @csrf
                                        <div class="d-flex align-items-center mb-3 pb-2">
                                            <i class="fas fa-cubes fa-1x me-3" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">Зарегистрировать</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Зарегистрируйтесь
                                            здесь</h5>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            @error('name')
                                            <label class="form-label" for="form2Example18" style="color: red">{{ $message }}</label>
                                            @enderror
                                            <input name="name" type="text" id="form2Example18"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example18">Имя </label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">
                                            @error('email')
                                            <label class="form-label" for="form2Example18" style="color: red">{{ $message }}</label>
                                            @enderror
                                            <input name="email" type="email" id="form2Example17"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example17">Email</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">

                                            @error('password')
                                            <label class="form-label" for="form2Example18" style="color: red">{{ $message }}</label>
                                            @enderror

                                            <input name="password" type="password" id="form2Example27"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example27">Пароль</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-3">

                                            @error('password_confirmation')
                                            <label class="form-label" for="form2Example18">{{ $message }}</label>
                                            @enderror

                                            <input name="password_confirmation" type="password" id="form2Example28"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example28">Ещё раз пишите пароль</label>
                                        </div>

                                        <div class="pt-1 mb-1">
                                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                                    class="btn btn-dark btn-lg btn-block">
                                                Зарегистрироваться
                                            </button>
                                        </div>

                                        <p class="mb-1 pb-lg-1 log" style="color: #393f81;">Если у вас есть учетная запись?
                                            <a href="{{ route('login') }}"
                                               style="color: #393f81;">Войти здесь</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


