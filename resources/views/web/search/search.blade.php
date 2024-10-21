@extends('layouts.main')

@section('content')
    @include('layouts.includes.menu')

    <div class="audios_1">
        <div class="medium-Class">
            <div class="audios-Class sear">
                <h1 class="audios-Class-h1">Категория корона</h1>
                @if($qurans->isEmpty())
                    <label class="form-label lab-sear" for="form2Example18" style="color: red">Не найдено</label>
                @else
                    @foreach($qurans as $quran)
                        <div class="audi-string">
                            <span class="audi-string-span">1.</span>
                            <p class="audi-string-p">{{ $quran->title }}</p>
                            <div class="audi-string-div">2:19</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="audios_1">
        <div class="medium-Class">
            <div class="audios-Class sear">
                <h1 class="audios-Class-h1">Категория нашида</h1>
                @if($nashids->isEmpty())
                    <label class="form-label lab-sear" for="form2Example18" style="color: red">Не найдено</label>
                @else
                    @foreach($nashids as $nashid)
                        <div class="audi-string">
                            <span class="audi-string-span">1.</span>
                            <p class="audi-string-p">{{ $nashid->title }}</p>
                            <div class="audi-string-div">2:19</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection

