
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="img-fluid" src="/img/mosque.png"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('quran') }}">Коран</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('nashid') }}">Нашид</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Азкары
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Утренние азкары</a></li>
                        <li><a class="dropdown-item" href="#">Вечерние азкары</a></li>
                        <li><a class="dropdown-item" href="#">После молитвы азкары</a></li>
                    </ul>
                </li>
            </ul>
            <form method="POST" action="{{ route('search') }}" class="d-flex" role="search">
                @csrf
                <input name="search" class="form-control me-2" type="search" required="" placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success" type="submit">Поиск</button>
            </form>
            <a href="{{ route('registration') }}" class="btn btn-primary">Регистрация</a>
        </div>
    </div>
</nav>
