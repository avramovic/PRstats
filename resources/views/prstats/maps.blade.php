@extends('layouts.prstats')

@section('title')
    Maps
@endsection

@section('subtitle')
    All maps ({{ $maps->count() }})
@endsection

@section('content')
    @foreach($maps as $map)
        @include('partials.maps.single')
    @endforeach
@endsection