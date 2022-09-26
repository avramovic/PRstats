@extends('layouts.prstats')

@section('title')
    Claiming {{ $player->name }}
@endsection

@section('subtitle')
    Claiming player {{ $player->name }}
@endsection

@section('content')
    <div class="col-lg-6">
        <!-- /content-panel -->

        <p>To claim player <a href="{!! $player->getLink() !!}">{{ $player->name }}</a> as your own, you need to temporarily change your clan tag and put the following code as your clan tag: </p>

        <h4>{{ $claim->code }}</h4>
        
        <p>Step 1: Click on the arrow next to "PLAY" and choose "Select Profile"</p>
        <img src="/img/claim/1.png" alt="">
        <p>&nbsp;</p>
        <p>Step 2: Choose your player profile, enter the code (<strong>{{ $claim->code }}</strong>) as your clan tag, and press "Play"</p>
        <img src="/img/claim/2.png" alt="">
        <p>&nbsp;</p>
        <p>Remember not to use the code from the image but the one assigned to your claim request, which is: <strong>{{ $claim->code }}</strong></p>

        <p>Once you join the game and the server notices you, your claim request will be fulfilled and you will receive a confirmation email. After that, you can revert to your original clan tag.</p>

        @include('prstats.claim.details')



    </div>

    @component('partials.players.player_subs', ['players' => $claims, 'metric' => 'deleted_at'])
        Latest players claimed
    @endcomponent

    @component('partials.users.list', ['users' => $users, 'metric' => 'created_at'])
        Latest users registered
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