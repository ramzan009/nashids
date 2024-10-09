@extends('layouts.main')

@section('content')
    @include('layouts.includes.menu')


    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-75">
                <div class="col col-xl-11">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="img/photo.jpg"
                                     alt="login form" class="img-fluid"
                                     style="width: 470px; height: 530px; margin: 25px 0 0 25px; border-radius: 10px"/>
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form>

                                        <div class="d-flex align-items-center mb-3 pb-2">
                                            <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                            <span class="h1 fw-bold mb-0">Войти в аккаунт</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Войти
                                            здесь</h5>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="email" id="form2Example17"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example17">Email</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" id="form2Example27"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="form2Example27">Пароль</label>
                                        </div>

                                        <div class="pt-1 mb-4">
                                            <button data-mdb-button-init data-mdb-ripple-init
                                                    class="btn btn-dark btn-lg btn-block" type="button">
                                                Войти
                                            </button>
                                        </div>

                                        <p class="mb-5 pb-lg-2" style="color: #393f81;">Страницу на регистрации?
                                            <a href="{{ route('registration') }}"
                                               style="color: #393f81;">Назад</a></p>
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