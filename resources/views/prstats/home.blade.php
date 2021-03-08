@extends('layouts.prstats')

@section('title')
    Home
@endsection

@section('content')
    @component('partials.players.players_table', ['players' => $players, 'metric' => 'monthly'])
        <i class="fa fa-angle-right"></i> Top players in {{ date('F') }}
    @endcomponent

    @component('partials.players.player_list', ['players' => $newest, 'metric' => 'created_at'])
        Newest players
    @endcomponent

    @component('partials.players.player_list', ['players' => $longest, 'metric' => 'minutes_played'])
        Longest in game
    @endcomponent

    @component('partials.players.player_list', ['players' => $mostKills, 'metric' => 'total_kills'])
        Most kills
    @endcomponent

    @component('partials.players.player_list', ['players' => $mostDeaths, 'metric' => 'total_deaths'])
        Most deaths
    @endcomponent

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection