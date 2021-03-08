<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: #666 url({!! $match->getMapImageUrl('background') !!}); background-size: cover;">
        <i class="fa fa-map fa-4x"></i>
        <h4>MAP</h4>
        <h2>{{ $match->map }}</h2>
{{--        <h4><abbr title="{{ $lastMatch->updated_at->format('Y-m-d') }}">{{ $lastMatch->updated_at->diffForHumans() }}</abbr></h4>--}}
    </div>
</div>