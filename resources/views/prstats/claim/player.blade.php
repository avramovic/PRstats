@extends('layouts.prstats')

@section('title')
    Claiming player {{ $player->name }}
@endsection

@section('content')
    <div class="row content-panel">
        <div class="col-md-2 col-sm-6 col-xs-6 profile-text mt mb centered">
            <h4>{!! $player->formatScoreHtml('total_score') !!}</h4>
            <h6>TOTAL SCORE</h6>
            <h4>{!! $player->formatScoreHtml('total_kills') !!}</h4>
            <h6>TOTAL KILLS</h6>
            <h4>{!! $player->formatScoreHtml('total_deaths') !!}</h4>
            <h6>TOTAL DEATHS</h6>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 profile-text mt mb centered">
            <div class="right-divider">

                <h4>{{ $player->total_deaths == 0 ? $player->total_kills : round($player->total_kills/$player->total_deaths, 2) }}</h4>
                <h6>K/D RATIO</h6>
                <h4>{!! $matches->total() !!}</h4>
                <h6>MATCHES PLAYED</h6>
                <h4>
                    <abbr title="{{ round($player->minutesPlayed() / 60, 1) }} hour(s)">~{{ Carbon\Carbon::now()->addMinutes($player->minutesPlayed())->diffForHumans(null, true) }}</abbr>
                </h4>
                <h6>IN-GAME TIME</h6>
            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 col-sm-12 col-xs-12 profile-text">
            <h3>Claiming: {{ $player->name }}</h3>

            @if($player->clan_id)
                <h5>Member of the <a href="{{ $player->clan->getLink() }}">{{ $player->clan->name }}</a> clan</h5>
            @endif
            <h5>First seen <abbr
                        title="{{ $player->created_at->format('Y-m-d') }}">{{ $player->created_at->diffForHumans() }}</abbr>
            </h5>
            @if(!$player->wasSeenRecently())
                <h5>Last seen <abbr
                            title="{{ $player->updated_at->format('Y-m-d') }}">{{ $player->updated_at->diffForHumans() }}</abbr>
                </h5>
            @endif

            @if($player->country)
                <p>{!! $player->getCountryFlagHtml() !!}</p>
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
        <div class="col-md-4 col-sm-12 col-xs-12 centered">
            <div class="profile-pic">
                <p><img data-pid="{{ $player->pid }}" onerror="reloadImage(this)" src="{!! $player->getAvatarUrl() !!}"
                        alt="We believe that {!! htmlentities($player->name) !!} looks like this :)"
                        title="We believe that {!! htmlentities($player->name) !!} looks like this :)"/></p>

            </div>
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-12">
            <p>To claim {{ $player->name }} player profile, you need to </p>
        </div>
    </div>


@endsection

@section('header')
@endsection

@section('scripts')
    <script>

        {{--$(window).load(function () {--}}
        {{--    OneSignal.push(function () {--}}
        {{--        OneSignal.isPushNotificationsEnabled(function (isEnabled) {--}}
        {{--            if (isEnabled) {--}}
        {{--                OneSignal.getUserId(function (userId) {--}}
        {{--                    $.ajax({--}}
        {{--                        url: "/json/subscribe/check",--}}
        {{--                        type: 'post',--}}
        {{--                        dataType: "json",--}}
        {{--                        data: {--}}
        {{--                            player_id: $('#sub-btn').data('pid'),--}}
        {{--                            device_uuid: userId,--}}
        {{--                            "_token": {!! json_encode(csrf_token()) !!},--}}
        {{--                        },--}}
        {{--                        success: function (data) {--}}
        {{--                            if (data.subscription) {--}}
        {{--                                showSubscribed();--}}
        {{--                            } else {--}}
        {{--                                showUnsubscribed();--}}
        {{--                            }--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}

        {{--function toggleSubscription(pid) {--}}
        {{--    OneSignal.getUserId(function (userId) {--}}
        {{--        $.ajax({--}}
        {{--            url: "/json/subscribe",--}}
        {{--            type: 'post',--}}
        {{--            dataType: "json",--}}
        {{--            data: {--}}
        {{--                player_id: pid,--}}
        {{--                device_uuid: userId,--}}
        {{--                "_token": {!! json_encode(csrf_token()) !!},--}}
        {{--            },--}}
        {{--            success: function (data) {--}}
        {{--                updateSubCount(data.count);--}}

        {{--                if (data.subscription) {--}}
        {{--                    showSubscribed();--}}
        {{--                } else {--}}
        {{--                    showUnsubscribed();--}}
        {{--                }--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}

        {{--}--}}

    </script>
@endsection