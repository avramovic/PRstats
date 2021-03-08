@extends('layouts.prstats')

@section('title')
    {{ $match->map }} on {{ $match->server->name }}
@endsection

@section('subtitle')
    @if($match->wasSeenRecently())
        Playing <u>{{ $match->map }}</u> since {{ $match->created_at->format('Y-m-d') }} at {{ $match->created_at->format('H:i') }} ({{ $match->lengthForHumans() }}) on <a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a>
    @else
        Played <u>{{ $match->map }}</u> on {{ $match->created_at->format('Y-m-d') }} from {{ $match->created_at->format('H:i') }} to {{ $match->updated_at->format('H:i') }} ({{ $match->lengthForHumans() }}) on <a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a>
    @endif
@endsection

@section('content')

    @component('partials.servers.current_match', ['match' => $match])
    @endcomponent

    <div class="row mt">
        @include('partials.servers.single', ['server' => $match->server])
    </div>

@endsection

@section('header')
    <script src="/lib/chart-master/Chart.js"></script>
@endsection

@section('scripts')
    <script src="/lib/sparkline-chart.js"></script>
@endsection