<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: #666 url({!! $lastMatch->getMapImageUrl('background') !!}); background-size: cover;">
        <i title="OFFLINE" class="fa fa-eye-slash fa-4x"></i>
        <h4>LAST SEEN PLAYING</h4>
        <h2>{{ $lastMatch->map }}</h2>
        <h4><abbr title="{{ $lastMatch->updated_at->format('Y-m-d') }}">{{ $lastMatch->updated_at->diffForHumans() }}</abbr></h4>
    </div>
</div>