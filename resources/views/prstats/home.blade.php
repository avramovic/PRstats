@extends('layouts.prstats')

@section('title')
    Home
@endsection

@section('content')
    @component('partials.players.players_table', ['players' => $players, 'metric' => 'monthly'])
        <i class="fa fa-angle-right"></i> Top players in {{ date('F') }}
    @endcomponent

    @include('partials.stats_daily', ['table' => 'players', 'field' => 'updated_at'])
    @include('partials.stats_weekly', ['table' => 'players', 'field' => 'updated_at'])

    @component('partials.players.player_list', ['players' => $mostKills, 'metric' => 'monthly_kills'])
        Most kills in {{ date('F') }}
    @endcomponent

    @component('partials.players.player_list', ['players' => $mostDeaths, 'metric' => 'monthly_deaths'])
        Most deaths in {{ date('F') }}
    @endcomponent

    @component('partials.stats_daily', ['table' => 'matches'])
        DAILY MATCHES PLAYED

        @slot('subtitle')
            matches played per day<br />(last 7 days)
        @endslot
    @endcomponent

    @component('partials.stats_weekly', ['table' => 'matches'])
        WEEKLY MATCHES PLAYED

        @slot('subtitle')
            matches played per week<br />(last 12 weeks)
        @endslot
    @endcomponent

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection