@extends('layouts.prstats')

@section('title')
    Players
@endsection

@section('content')
    @component('partials.players.players_table', ['players' => $players, 'metric' => 'total'])
        <i class="fa fa-angle-right"></i> Top players of all time
    @endcomponent

    @component('partials.players.player_list', ['players' => $mostKills, 'metric' => 'total_kills'])
        Most kills of all time
    @endcomponent

    @component('partials.players.player_list', ['players' => $mostDeaths, 'metric' => 'total_deaths'])
        Most deaths of all time
    @endcomponent

    @include('partials.stats_daily', ['table' => 'players'])
    @include('partials.stats_weekly', ['table' => 'players'])

    @component('partials.players.player_list', ['players' => $newest, 'metric' => 'created_at'])
        Newest players
    @endcomponent

    @component('partials.players.player_list', ['players' => $longest, 'metric' => 'minutes_played'])
        Longest in-game
    @endcomponent

@endsection