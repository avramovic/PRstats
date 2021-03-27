@extends('layouts.prstats')

@section('title')
    {{ $map->name }}
@endsection

@section('content')

    <div class="row mt">
        @include('partials.maps.activity')
        @include('partials.maps.layout')
        @include('partials.maps.activity_weeks')
    </div>

    @include('partials.maps.previous_matches', ['matches' => $matches])

@endsection

@section('header')
@endsection

@section('scripts')
@endsection