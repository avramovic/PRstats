@extends('layouts.prstats')

@section('title')
    {{ $clan->name }}
@endsection

@section('content')
    @include('partials.players.clan_table')
@endsection