@extends('layouts.prstats')

@section('title')
    {{ $server->name }}
@endsection

@section('content')
    <div class="row content-panel centered">
        @if(filter_var($server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $server->community_website }}" target="_blank"><img src="{{ $server->server_logo }}"
                                                                               alt="{{ $server->name }} logo"
                                                                               onerror="$(this).hide()"
                                                                               class="server-logo img-fluid"/></a></p>
        @else
            <p><img src="{{ $server->server_logo }}" alt="{{ $server->name }} logo" onerror="$(this).hide()"
                    class="server-logo img-fluid"/></p>
        @endif
    </div>
    <div class="row content-panel">
        <div class="col-md-4 profile-text mt mb centered">
            <div class="right-divider">
                <h4>{!! $server->formatScoreHtml('total_score') !!}</h4>
                <h6>TOTAL SCORE</h6>
                <h4>{{ $server->players->count() }}</h4>
                <h6>TOTAL PLAYERS</h6>
                <h4>{!! $server->formatScoreHtml('total_kills') !!}</h4>
                <h6>TOTAL KILLS</h6>
                <h4>{!! $server->formatScoreHtml('total_deaths') !!}</h4>
                <h6>TOTAL DEATHS</h6>
            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 profile-text">
            <h3>{{ $server->name }}</h3>
{{--            <p>COUNTRY: {{ $server->country }} <br/> OS: <i title="{{ $server->os }}"--}}
{{--                                                            class="fa {{ stripos($server->os, 'win') !== false ? 'fa-windows' : 'fa-linux'  }}"></i>--}}
{{--            </p>--}}
            @if(!empty($server->server_logo))
                <p>{!! str_replace('|', '<br />', $server->server_text) !!}</p>
            @endif
            <p>{!! $server->getCountryFlagHtml() !!}</p>
            {{--            <p>Contrary to popu?lar belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC.</p>--}}
            <br>
            <p>
                @if($server->community_website)
                    <a class="btn btn-theme" href="{{ $server->community_website }}" target="_blank"><i
                                class="fa fa-globe"></i> Website</a>
                @endif
                @if($server->br_download)
                    <a class="btn btn-theme" href="{{ $server->br_download }}" target="_blank"><i
                                class="fa fa-camera"></i> Battle records</a>
                @endif

            </p>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 centered">
            <div class="profile-pic">
                <p>{!! $server->getCountryFlagHtml(64) !!}</p>
                <p>
                    <button class="btn btn-theme"><i class="fa fa-check"></i> Follow</button>
                    <button class="btn btn-theme02">Add</button>
                </p>
            </div>
        </div>
        <!-- /col-md-4 -->
    </div>


    @if($server->wasSeenRecently())
        <div class="row mt">
            @include('partials.servers.activity')
            @include('partials.servers.current_map')
            @include('partials.servers.capacity')
        </div>
    @else
        <div class="row mt">
            @include('partials.servers.activity')
            @include('partials.servers.last_seen')
            @include('partials.servers.activity_weeks')
        </div>
    @endif

    @if($server->wasSeenRecently())
        @component('partials.servers.current_match', ['match' => $lastMatch])
            @if($lastMatch->server->wasSeenRecently())
                <h3><i class="fa fa-angle-right"></i> Playing <u>{{ $lastMatch->map }}</u> since {{ $lastMatch->created_at->format('Y-m-d') }} at {{ $lastMatch->created_at->format('H:i') }} ({{ $lastMatch->lengthForHumans() }}) </h3>
            @else
                <h3><i class="fa fa-angle-right"></i> Played <u>{{ $lastMatch->map }}</u> on {{ $lastMatch->created_at->format('Y-m-d') }} from {{ $lastMatch->created_at->format('H:i') }} to {{ $lastMatch->updated_at->format('H:i') }} ({{ $lastMatch->lengthForHumans() }})</h3>
            @endif
        @endcomponent
    @endif

    @include('partials.servers.previous_matches', ['matches' => $previousMatches])

@endsection

@section('header')
    <script src="/lib/chart-master/Chart.js"></script>
@endsection

@section('scripts')
    <script src="/lib/sparkline-chart.js"></script>
@endsection