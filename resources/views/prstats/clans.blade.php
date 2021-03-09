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

@endsection