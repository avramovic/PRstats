@extends('layouts.prstats')

@section('title')
    Maps
@endsection

@section('subtitle')
    All maps
@endsection

@section('content')
    @foreach($maps as $map)
        @include('partials.maps.single')
    @endforeach
@endsection