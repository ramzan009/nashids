@extends('layouts.main')

@section('title', 'Страница1')

@section('content')
    @include('layouts.includes.menu')


    @include('layouts.includes.audio', ['title' => 'Коран'])

@endsection
