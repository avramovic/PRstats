<div class="col-md-4 col-sm-4 mb">
    <div class="weather pn"
         style="background: url({{ $map->getOriginalMapImageUrl('background') }}), url({{ $map->getOriginalMapImageUrl('background', true) }}); background-color: #666; background-size: cover;">
        <i style="visibility: hidden" class="fa fa-map fa-4x"></i>
{{--        <h4>MAP</h4>--}}
        <h2>{{ $map->name }}</h2>
{{--        <h4>ses</h4>--}}
    </div>
</div>