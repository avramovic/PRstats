<div class="col-lg-4 col-md-4 col-sm-4 mb">
    <!-- WHITE PANEL - TOP USER -->
    <div class="white-panel pn">
        <div class="white-header">
            <h5>PLAYER PROFILE</h5>
        </div>
        <p><img onerror="reloadImage(this)" src="{!! $player->getAvatarUrl() !!}" class="img-circle" width="50"></p>
        <p><b><a href="{{ $player->getLink() }}">{{ $player->name }}</a></b></p>
        <div class="row">
            <div class="col-md-4">
                <p class="small mt">FIRST SEEN</p>
                <p><abbr title="{{ $player->created_at->format('Y-m-d') }}">{{ $player->created_at->diffForHumans() }}</abbr></p>
            </div>
            <div class="col-md-4">
                <p class="small mt">LAST SEEN</p>
                <p><abbr title="{{ $player->updated_at->format('Y-m-d') }}">{{ $player->updated_at->diffForHumans() }}</abbr></p>
            </div>
            <div class="col-md-4">
                <p class="small mt">IN-GAME TIME</p>
                <p><abbr title="{{ round($player->minutesPlayed() / 60, 1) }} hour(s)">~{{ Carbon\Carbon::now()->addMinutes($player->minutesPlayed())->diffForHumans(null, true) }}</abbr></p>
            </div>
        </div>
    </div>
</div>