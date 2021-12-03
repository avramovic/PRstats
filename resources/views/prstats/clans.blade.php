@extends('layouts.prstats')

@section('title')
    Players
@endsection

@section('content')
    @component('partials.clans.clans_table', ['clans' => $clans])
        <i class="fa fa-angle-right"></i> Top clans of all times
    @endcomponent

    @include('partials.stats_daily', ['table' => 'clans'])
    @include('partials.stats_weekly', ['table' => 'clans'])

    @component('partials.clans.clan_list', ['clans' => $newest, 'metric' => 'created_at'])
        Newest clans
    @endcomponent

    @component('partials.clans.clan_list', ['clans' => $populous, 'metric' => 'players_count'])
        Most populous clans
    @endcomponent

@endsection