@extends('layouts.prstats')

@section('title')
    {{ $map->name }}
@endsection

@section('content')

{{--    @if($server->wasSeenRecently())--}}
{{--        <div class="row mt">--}}
{{--            @include('partials.servers.activity')--}}
{{--            @include('partials.servers.current_map')--}}
{{--            @include('partials.servers.capacity')--}}
{{--        </div>--}}
{{--    @else--}}
{{--        <div class="row mt">--}}
{{--            @include('partials.servers.activity')--}}
{{--            @include('partials.servers.last_seen')--}}
{{--            @include('partials.servers.activity_weeks')--}}
{{--        </div>--}}
{{--    @endif--}}


    @include('partials.maps.previous_matches', ['matches' => $matches])

@endsection

@section('header')
@endsection

@section('scripts')
@endsection