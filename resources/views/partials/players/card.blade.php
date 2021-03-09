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
                <p class="small mt">TOTAL SCORE</p>
                <p>{!! $player->formatScoreHtml('total_score') !!}</p>
            </div>
            <div class="col-md-4">
                <p class="small mt">TOTAL KILLS</p>
                <p>{!! $player->formatScoreHtml('total_kills') !!}</p>
            </div>
            <div class="col-md-4">
                <p class="small mt">TOTAL DEATHS</p>
                <p>{!! $player->formatScoreHtml('total_deaths') !!}</p>
            </div>
        </div>
    </div>
</div>