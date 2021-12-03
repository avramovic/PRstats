<div class="col-lg-3 ds mb">
    <h4>{{ $slot }}</h4>
    @foreach($clans as $clan)
    <div class="desc">
        <div class="thumb">
            <img class="img-circle" src="{{ $clan->country ? $clan->getCountryFlagUrl(48) : '/img/logo.png' }}" width="35" height="35" align="">
        </div>
        <div class="details">
            <p>
                <a href="{{ $clan->getLink() }}">{{ $clan->name }}</a><br/>
                <em>
                    @if($metric==='created_at')
                        {{ $clan->created_at->diffForHumans() }}
                    @elseif($metric==='players_count')
                        {{ $clan->players_count }} player(s)
{{--                    @else--}}
{{--                        {!! $clan->formatScoreHtml($metric) !!} {{ str_replace(['total_', 'monthly_'], '', $metric) }}--}}
                    @endif
                </em>
            </p>
        </div>
    </div>
    @endforeach
</div>