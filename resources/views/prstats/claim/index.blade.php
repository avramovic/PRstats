@extends('layouts.prstats')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="col-lg-6">
        <h4>Claiming player {{ isset($player) ? $player->name : 'profiles' }}</h4>
        <section id="unseen">
            <p>To claim player {{ isset($player) ? $player->name : 'profiles' }} as your own, you need use a search in the top-right corner of this page and find the player profile.</p>
        </section>
        <!-- /content-panel -->

        <button id="btn-login">log in</button>

        <h4>Your subscriptions</h4>
        <div id="subscriptions">
            <p>Allow notifications to list your subscriptions.</p>
        </div>
    </div>

    @component('partials.players.player_subs', ['players' => $latest, 'metric' => 'created_at'])
        Latest followed
    @endcomponent

    @component('partials.players.player_subs', ['players' => $most, 'metric' => 'subscriptions_count'])
        Most followers
    @endcomponent


@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        {{--$(window).load(function () {--}}
        {{--    OneSignal.push(function () {--}}
        {{--        OneSignal.isPushNotificationsEnabled(function (isEnabled) {--}}
        {{--            if (isEnabled) {--}}
        {{--                OneSignal.getUserId(function (userId) {--}}
        {{--                    $.ajax({--}}
        {{--                        url: "/json/notifications/get",--}}
        {{--                        type: 'post',--}}
        {{--                        dataType: "html",--}}
        {{--                        data: {--}}
        {{--                            device_uuid: userId,--}}
        {{--                            "_token": {!! json_encode(csrf_token()) !!},--}}
        {{--                        },--}}
        {{--                        success: function (data) {--}}
        {{--                            $('#subscriptions').html(data);--}}
        {{--                        }--}}
        {{--                    });--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}


        $('#btn-login').on('click', async function () {
            const { value: email } = await Swal.fire({
                title: 'Input email address',
                input: 'email',
                inputLabel: 'Your email address',
                inputPlaceholder: 'Enter your email address'
            })

            if (email) {
                $.ajax({
                    url: "/json/login",
                    type: 'post',
                    dataType: "json",
                    data: {
                        email: email,
                        "_token": {!! json_encode(csrf_token()) !!},
                    },
                    success: function (data) {
                        Swal.fire(
                            'Good job!',
                            data.message,
                            'success'
                        )
                    }
                });

            }
        });
    </script>
@endsection