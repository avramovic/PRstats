<div class="col-lg-3 ds mb">
    <h4>{{ $slot }}</h4>
    @foreach($users as $user)
    <div class="desc">
        <div class="thumb">
            <img data-pid="{{ md5(strtolower($user->email)) }}" onerror="reloadImage(this)" class="img-circle" src="{{ $user->getAvatarUrl(35) }}" width="35" height="35" align="">
        </div>
        <div class="details">
            <p>
                <a href="{{ $user->getLink() }}">{{ $user->name }}</a><br/>
                <em>
                    @if(\Illuminate\Support\Str::endsWith($metric, '_at'))
                        {{ $user->{$metric}->diffForHumans() }}
                    @else
                        {!! $user->formatValueHtml($user->$metric) !!} {{ $user->$metric == 1 ? \Illuminate\Support\Str::singular(str_replace(['_count'], '', $metric)) : str_replace(['_count'], '', $metric) }}
                    @endif
                </em>
            </p>
        </div>
    </div>
    @endforeach
</div>