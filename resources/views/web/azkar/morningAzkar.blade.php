@extends('layouts.main')

@section('content')
    @include('layouts.includes.menu')
        <div class="block-az">
            @foreach($azkars as $azkar)
            <div class="block-az-2">
                <div class="block-value">
                    <h1 class="block-value-h1">{{ $azkar->title }}</h1>
                    <div class="block-value-p-div">
                        <p class="block-value-p">{{ $azkar->content_arabic }}</p>
                    </div>
                    <div class="block-value-div">{{ $azkar->content_rus }}</div>
                </div>
            </div>
            @endforeach
            <br>
            <br>
        </div>
@endsection
