@extends('layouts.prstats')

@section('title')
    {{ $player->name }}
@endsection

@section('content')

    @if(!Auth::guest() && (Auth::user()->canEdit($player)))
        @include('partials.players.admin')
    @endif

    <div class="row content-panel">
        <div class="col-md-2 col-sm-6 col-xs-6 profile-text mt mb centered">
            <h4>{!! $player->formatScoreHtml('total_score') !!}</h4>
            <h6>TOTAL SCORE</h6>
            <h4>lp{!! $player->formatScoreHtml('total_kills') !!}</h4>
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
            <h3>{{ $player->name }}
            @if(!empty($player->user_id))
                <i class="fa fa-check-circle-o" title="This player has been claimed by someone!"></i>
            @endif
            </h3>

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

            @if(!empty($player->user_id))
                <h5>Claimed by: <a href="{{ $player->user->getLink() }}">{{ $player->user->name }}</a></h5>
{{--                @if($player->user->location)<h5>Location: {{ $player->user->location }}</h5>@endif--}}
{{--                @if($player->user->bio)<p>{{ $player->user->bio }}</p>@endif--}}
            @endif
        </div>
        <!-- /col-md-4 -->
        <div class="col-md-4 col-sm-12 col-xs-12 centered">
            <div class="profile-pic">
                <p><img data-pid="{{ $player->pid }}" onerror="reloadImage(this)" src="{!! $player->getAvatarUrl() !!}"
                        alt="We believe that {!! htmlentities($player->name) !!} looks like this :)"
                        title="We believe that {!! htmlentities($player->name) !!} looks like this :)"/></p>
                <p>
                    <button id="sub-btn" data-pid="{{ $player->id }}" class="btn btn-theme">
                        <i class="fa fa-bell" id="sub-icon"></i> <span id="sub-label">Subscribe</span> (<span
                                id="sub-cnt">{{ $player->subscriptions_count }}</span>)
                    </button>
                    @if(empty($player->user_id))
                        @if(Auth::guest())
                            <a href="{{ route('claim.index') }}" class="btn btn-danger">
                                <i class="fa fa-legal" id="claim-icon"></i> <span id="claim-label">Claim</span>
                            </a>
                        @else
                            <button type="button" class="btn btn-danger claim-btn">
                                <i class="fa fa-legal" id="claim-icon"></i> <span id="claim-label">Claim</span>
                            </button>
                        @endif
                    @endif
                </p>
                <p id="blokked" class="hidden">Disable adblock!</p>
            </div>
        </div>
        <!-- /col-md-4 -->
    </div>

    <form id="claim-form" method="post" action="{{ route('claim.store', $player->id) }}">
        @csrf
    </form>


    @if($player->wasSeenRecently())
        <div class="row mt">
            @include('partials.players.activity')
            @include('partials.players.current_map')
            @include('partials.players.activity_weeks')
        </div>
    @else
        <div class="row mt">
            @include('partials.players.activity')
            @include('partials.players.last_seen')
            @include('partials.players.activity_weeks')
        </div>
    @endif

    @include('partials.players.previous_matches', ['matches' => $matches])

    @if($hasSignature)
        <div class="row mt">
            @include('partials.players.signature')
        </div>
    @else
        <div class="row mt">
            @include('partials.players.no-signature')
        </div>
    @endif


@endsection

@section('header')
    <script src="/lib/chart-master/Chart.js"></script>
@endsection

@section('scripts')
    <script src="/lib/sparkline-chart.js"></script>
    <script>

        $('#sub-btn').on('click', function (el) {

            if (!$('#blokked').hasClass('hidden')) {
                window.location = '/notifications';
                el.preventDefault();
            }

            OneSignal.push(["getNotificationPermission", function (permission) {
                switch (permission) {
                    case 'default':
                        OneSignal.showNativePrompt();
                        break;
                    case 'granted':
                        toggleSubscription($('#sub-btn').data('pid'));
                        break;
                    case 'denied':
                        window.location = '/notifications';
                        break;
                }
            }]);
        });


        $(window).load(function () {
            OneSignal.push(function () {
                OneSignal.isPushNotificationsEnabled(function (isEnabled) {
                    if (isEnabled) {
                        OneSignal.getUserId(function (userId) {
                            $.ajax({
                                url: "/json/subscribe/check",
                                type: 'post',
                                dataType: "json",
                                data: {
                                    player_id: $('#sub-btn').data('pid'),
                                    device_uuid: userId,
                                    "_token": {!! json_encode(csrf_token()) !!},
                                },
                                success: function (data) {
                                    if (data.subscription) {
                                        showSubscribed();
                                    } else {
                                        showUnsubscribed();
                                    }
                                }
                            });
                        });
                    }
                });
            });
        });


        function toggleSubscription(pid) {
            OneSignal.getUserId(function (userId) {
                $.ajax({
                    url: "/json/subscribe",
                    type: 'post',
                    dataType: "json",
                    data: {
                        player_id: pid,
                        device_uuid: userId,
                        "_token": {!! json_encode(csrf_token()) !!},
                    },
                    success: function (data) {
                        updateSubCount(data.count);

                        if (data.subscription) {
                            showSubscribed();
                        } else {
                            showUnsubscribed();
                        }
                    }
                });
            });

        }

        function showSubscribed() {
            $('#sub-icon').removeClass('fa-bell').addClass('fa-bell-slash');
            $('#sub-label').text('Unsubscribe');
        }

        function showUnsubscribed() {
            $('#sub-icon').removeClass('fa-bell-slash').addClass('fa-bell');
            $('#sub-label').text('Subscribe');
        }

        function updateSubCount(val) {
            $('#sub-cnt').text(val);
        }


        $('.claim-btn').on('click', async function (el) {

            const { value: result } = await Swal.fire({
                title: 'Are you sure you want to claim player "'+{!! json_encode($player->name) !!}+'" as your own?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Claim',
                denyButtonText: `Don't claim`,
            });

            if (result) {
                $('#claim-form').submit();
            }

        });

        // function confirmClaim(btn) {
        //     let form = $(btn).parents('form:first');
        //     console.log(form);
        //     Swal.fire({
        //         title: 'Are you sure you want to claim this player profile as your own?',
        //         showDenyButton: true,
        //         showCancelButton: false,
        //         confirmButtonText: 'Claim',
        //         denyButtonText: `Don't claim`,
        //     }).then((result) => {
        //         /* Read more about isConfirmed, isDenied below */
        //         if (result.isConfirmed) {
        //             form.submit();
        //         }
        //     })
        // }


    </script>
@endsection