<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: #666 url({!! $lastMatch->getMapImageUrl('background') !!}); background-size: cover;">
        <i class="fa fa-gamepad fa-4x"></i>
        <h4>CURRENTLY PLAYING</h4>
        <h2>{{ $lastMatch->map }}</h2>
        <h4>on <a href="{{ $lastMatch->server->getLink() }}">{{ $lastMatch->server->name }}</a></h4>
    </div>
</div>