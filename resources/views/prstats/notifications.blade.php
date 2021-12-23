@extends('layouts.prstats')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="col-lg-6">
        <h4>Notifications</h4>
        <section id="unseen">
            <p>Allow push notifications by clicking the button below in order to be able to subscribe to player activity. Once you allow site-wide push notifications, you'll be able to subscribe to individual players.</p>
            <div class='onesignal-customlink-container'></div>
            <p class="hidden strong" id="blokked">Disable your ad-blocker in order to see the subscription button. We don't have ads anyway.</p>
            <p>Once you allow subscriptions, you'll be able to subscribe to individual players. When you subscribe to a player, you will be notified each time they start playing in a public server.</p>
        </section>
        <!-- /content-panel -->


        <h4>Your subscriptions</h4>
        <div id="subscriptions">
            <p>Allow notifications to list your subscriptions.</p>
        </div>
    </div>

    @component('partials.players.player_subs', ['players' => $latest, 'metric' => 'created_at'])
        Latest subscriptions
    @endcomponent

    @component('partials.players.player_subs', ['players' => $most, 'metric' => 'subscriptions_count'])
        Most subscriptions
    @endcomponent


@endsection

@section('scripts')
    <script type="text/javascript">
        $(window).load(function () {
            OneSignal.push(function () {
                OneSignal.isPushNotificationsEnabled(function (isEnabled) {
                    if (isEnabled) {
                        OneSignal.getUserId(function (userId) {
                            $.ajax({
                                url: "/json/notifications/get",
                                type: 'post',
                                dataType: "html",
                                data: {
                                    device_uuid: userId,
                                    "_token": {!! json_encode(csrf_token()) !!},
                                },
                                success: function (data) {
                                    $('#subscriptions').html(data);
                                }
                            });
                        });
                    }
                });
            });
        });
    </script>
@endsection