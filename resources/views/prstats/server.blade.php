@extends('layouts.prstats')

@section('title')
    {{ $server->name }}
@endsection

@section('content')
    <div class="row centered">
        <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
        @if(filter_var($server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $server->community_website }}" target="_blank"><img src="{{ $server->server_logo }}"
                                                                               alt="{{ $server->name }} logo"
                                                                               onerror="$(this).hide()"
                                                                               class="server-logo img-responsive"/></a></p>
        @else
            <p><img src="{{ $server->server_logo }}" alt="{{ $server->name }} logo" onerror="$(this).hide()"
                    class="server-logo img-responsive"/></p>
        @endif
        </div>
    </div>

    <div class="row mt content-panel">
        <div class="col-md-2 col-sm-6 col-xs-6 profile-text mt mb centered">
            <h4>{!! $server->formatScoreHtml('total_score') !!}</h4>
            <h6>TOTAL SCORE</h6>
            <h4>{{ $server->playerCount() }}</h4>
            <h6>UNIQUE PLAYERS</h6>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 profile-text mt mb centered">
            <div class="right-divider">
                <h4>{!! $server->formatScoreHtml('total_kills') !!}</h4>
                <h6>TOTAL KILLS</h6>
                <h4>{!! $server->formatScoreHtml('total_deaths') !!}</h4>
                <h6>TOTAL DEATHS</h6>
                <h4>{{ $server->matches_count }}</h4>
                <h6>MATCHES PLAYED</h6>
            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-8 col-sm-12 col-xs-12 profile-text">
            <h3>{{ $server->name }}</h3>
            @if(!empty($server->server_logo))
                <p>{!! str_replace('|', '<br />', $server->server_text) !!}</p>
            @endif
            <p>{!! $server->getCountryFlagHtml() !!}</p>
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
@endsection

@section('scripts')
@endsection