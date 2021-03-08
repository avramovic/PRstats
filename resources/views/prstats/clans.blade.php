@extends('layouts.prstats')

@section('title')
    Players
@endsection

@section('content')
    @component('partials.clans.clans_table', ['clans' => $clans])
        <i class="fa fa-angle-right"></i> Top clans of all times
    @endcomponent

    @include('partials.daily_new', ['table' => 'clans'])
    @include('partials.weekly_new', ['table' => 'clans'])

@endsection