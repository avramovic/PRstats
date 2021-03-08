@extends('layouts.prstats')

@section('title')
    Players
@endsection

@section('content')
    @component('partials.players.clans_table', ['clans' => $clans])
        <i class="fa fa-angle-right"></i> Top clans of all times
    @endcomponent

{{--    @component('partials.players.player_list', ['players' => $newest, 'metric' => 'created_at'])--}}
{{--        Newest players--}}
{{--    @endcomponent--}}

{{--    @component('partials.players.player_list', ['players' => $longest, 'metric' => 'minutes_played'])--}}
{{--        Longest in game--}}
{{--    @endcomponent--}}

{{--    @component('partials.players.player_list', ['players' => $mostKills, 'metric' => 'total_kills'])--}}
{{--        Most kills--}}
{{--    @endcomponent--}}

{{--    @component('partials.players.player_list', ['players' => $mostDeaths, 'metric' => 'total_deaths'])--}}
{{--        Most deaths--}}
{{--    @endcomponent--}}

@endsection