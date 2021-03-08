@extends('layouts.prstats')

@section('title')
    {{ $player->name }}
@endsection

@section('content')
    <div class="row content-panel">
        <div class="col-md-2 profile-text mt mb centered">
                <h4>{!! $player->formatScoreHtml('total_score') !!}</h4>
                <h6>TOTAL SCORE</h6>
                <h4>{!! $player->formatScoreHtml('total_kills') !!}</h4>
                <h6>TOTAL KILLS</h6>
                <h4>{!! $player->formatScoreHtml('total_deaths') !!}</h4>
                <h6>TOTAL DEATHS</h6>

        </div>
        <div class="col-md-2 profile-text mt mb centered">
            <div class="right-divider">

                <h4>{{ $player->total_deaths == 0 ? $player->total_kills : round($player->total_kills/$player->total_deaths, 2) }}</h4>
                <h6>K/D RATIO</h6>
                <h4>{!! $matches->total() !!}</h4>
                <h6>MATCHES PLAYED</h6>
                <h4><abbr title="{{ round($player->minutesPlayed() / 60, 1) }} hour(s)">~{{ Carbon\Carbon::now()->addMinutes($player->minutesPlayed())->diffForHumans(null, true) }}</h4>
                <h6>IN-GAME TIME</h6>
            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 profile-text">
            <h3>{{ $player->name }}</h3>

            @if($player->clan_id)
            <h5>Member of the <a href="{{ $player->clan->getLink() }}">{{ $player->clan->name }}</a> clan</h5>
            @endif
            <h5>First seen <abbr title="{{ $player->created_at->format('Y-m-d') }}">{{ $player->created_at->diffForHumans() }}</abbr></h5>
            @if(!$player->wasSeenRecently())
            <h5>Last seen <abbr title="{{ $player->updated_at->format('Y-m-d') }}">{{ $player->updated_at->diffForHumans() }}</abbr></h5>
            @endif

            @if($player->country)
            <p>{{ $player->getCountryFlagHtml() }}</p>
            @endif
            <br>
{{--            <p>--}}
{{--                @if($server->community_website)--}}
{{--                    <a class="btn btn-theme" href="{{ $server->community_website }}" target="_blank"><i--}}
{{--                                class="fa fa-globe"></i> Website</a>--}}
{{--                @endif--}}
{{--                @if($server->br_download)--}}
{{--                    <a class="btn btn-theme" href="{{ $server->br_download }}" target="_blank"><i--}}
{{--                                class="fa fa-camera"></i> Battle records</a>--}}
{{--                @endif--}}

{{--            </p>--}}
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 centered">
            <div class="profile-pic">
                <p><img onerror="reloadImage(this)" src="{!! $player->getAvatarUrl() !!}" alt="We believe that {!! htmlentities($player->name) !!} looks like this :)" title="We believe that {!! htmlentities($player->name) !!} looks like this :)" /></p>
{{--                <p>--}}
{{--                    <button class="btn btn-theme"><i class="fa fa-check"></i> Follow</button>--}}
{{--                    <button class="btn btn-theme02">Add</button>--}}
{{--                </p>--}}
            </div>
        </div>
        <!-- /col-md-4 -->
    </div>


    @if($player->wasSeenRecently())
        <div class="row mt">
            @include('partials.players.current_map')
{{--            @include('partials.players.activity')--}}
{{--            @include('partials.players.capacity')--}}
        </div>
    @else
        <div class="row mt">
            @include('partials.players.last_seen')
{{--            @include('partials.players.activity')--}}
{{--            @include('partials.players.activity_months')--}}
        </div>
    @endif

{{--    @if($server->wasSeenRecently())--}}
{{--        @include('partials.players.current_match', ['match' => $lastMatch])--}}
{{--    @endif--}}

    @include('partials.players.previous_matches', ['matches' => $matches])

@endsection

@section('header')
    <script src="/lib/chart-master/Chart.js"></script>
@endsection

@section('scripts')
    <script src="/lib/sparkline-chart.js"></script>
@endsection