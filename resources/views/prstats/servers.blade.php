@extends('layouts.prstats')

@section('title')
    Servers
@endsection

@section('subtitle')
    Servers active in the past 7 days
@endsection

@section('content')
    @foreach($servers as $server)
        @include('partials.servers.single')
    @endforeach
@endsection