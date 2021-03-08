@extends('layouts.prstats')

@section('title')
    {{ $clan->name }}
@endsection

@section('content')
    @include('partials.players.clan_table')
    @if($playerDetails)
        @include('partials.players.card', ['player' => $playerDetails])
    @endif
@endsection