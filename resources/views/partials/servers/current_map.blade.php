<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: #666 url({!! $lastMatch->getMapImageUrl('background') !!}); background-size: cover;">
        <i class="fa fa-gamepad fa-4x"></i>
        <h4>CURRENTLY PLAYING</h4>
        <h2>{{ $lastMatch->map->name }}</h2>
        <h4>{{ $lastMatch->lengthForHumans() }}</h4>
    </div>
</div>