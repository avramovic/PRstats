@extends('layouts.prstats')

@section('title')
    Claim profiles
@endsection

@section('subtitle')
    Claiming player profiles
@endsection

@section('content')
    <div class="col-lg-6">
        @if(Auth::guest())
            <p>To claim player profiles as your own, first you need to log in to the website. Please use a valid e-mail address as your login link will be sent to your e-mail.</p>
            <button class="btn-lg btn btn-theme" id="btn-login">LOG IN</button>
            <p>&nbsp;</p>
            <p>Once you log in, find the player profile using the search option in the top-right corner of this page, and click on the "Claim" button. </p>
            @include('prstats.claim.details')
        @else
            <p>To claim player profiles as your own, find the player profile using the search option in the top-right corner of this page, and click on the "Claim" button. </p>
            @include('prstats.claim.details')
            <h4>Player profiles you claimed</h4>
            <div id="subscriptions">
                <p>Allow notifications to list your subscriptions.</p>
            </div>
        @endif


    </div>

    @component('partials.players.player_subs', ['players' => $latest, 'metric' => 'created_at'])
        Latest followed
    @endcomponent

    @component('partials.players.player_subs', ['players' => $most, 'metric' => 'subscriptions_count'])
        Most followers
    @endcomponent


@endsection

@section('scripts')
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