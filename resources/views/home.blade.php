@extends('layouts.app')

@section('title')
    Top players in {{ date('F') }}
@endsection

@section('content')
    <table align="center">
        <thead>
            <tr>
                <th>#</th>
                <th></th>
                <th>Clan</th>
                <th></th>
                <th>Name</th>
                <th>Monthly score</th>
                <th>Monthly kills</th>
                <th>Monthly deaths</th>
                <th>Matches</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @foreach($players as $player)
        <tr>
            <td>{{ $nr++ }}</td>
            <td>{!! $player->clan ? $player->clan->getCountryFlagHtml() : '' !!}</td>
            <td>
                @if($player->clan)
                    <span class="clan"><a href="{{ $player->clan->getLink() }}">{{ $player->clan_name }}</a></span>
                @else
                    &mdash;
                @endif
            </td>
            <td>{!! $player->getCountryFlagHtml() !!}</td>
            <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
            <td>{!! $player->formatScoreHtml('monthly_score') !!}</td>
            <td>{!! $player->formatScoreHtml('monthly_kills') !!}</td>
            <td>{!! $player->formatScoreHtml('monthly_deaths') !!}</td>
            <td>{!! $player->matches_count !!}</td>
        </tr>
    @endforeach

        </tbody>
    </table>

    <!-- testing deployment -->
@endsection

@section('right')
    <h2>Welcome!</h2>
    <p>PR stats is a website which tracks your <a href="http://www.realitymod.com" target="_blank">Project Reality</a> stats in real-time. The table on the left shows top 50 players
        which have been playing in the last month and their monthly score.</p>
    <p>Due to PR being a frankenstein of a game, your stats won't count until you are in the top 64 players on the server.
        Consider that a feature, a nice way of filtering AFKers and lousy players :-)</p>
@endsection

@section('rightbottom')
    <div class="right">
        <div class="darkbg">
            <a href="{{ $newest->first()->getLink() }}">
                <img src="{{ $newest->first()->getAvatarUrl() }}" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Newest players:</h3>
            @foreach($newest as $new)
                <ul class="players">
                    <li>
                        @if($new->clan)<a href="{{ $new->clan->getLink() }}">{{ $new->clan->name }}@endif <a href="{{ $new->getLink() }}">{{ $new->name }}</a>
                        ({{ $new->created_at->diffForHumans() }})
                    </li>
                </ul>
            @endforeach
        </div>
    </div>


    <div class="right">
        <div class="darkbg">
            <a href="{{ $longest->first()->getLink() }}">
                <img src="{{ $longest->first()->getAvatarUrl() }}" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Longest in-game:</h3>
            @foreach($longest as $new)
                <ul class="players">
                    <li>
                        @if($new->clan)<a href="{{ $new->clan->getLink() }}">{{ $new->clan->name }}@endif <a href="{{ $new->getLink() }}">{{ $new->name }}</a>
                            (<abbr title="{{ round($new->minutes_played / 60, 1) }} hour(s)">{{ Carbon\Carbon::now()->addMinutes($new->minutes_played)->diffForHumans(null, true) }}</abbr>)
                    </li>
                </ul>
            @endforeach
        </div>
    </div>

    <div class="right">
        <div class="darkbg">
            <a href="{{ $mostKills->first()->getLink() }}">
                <img src="{{ $mostKills->first()->getAvatarUrl() }}" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Most kills:</h3>

            @foreach($mostKills as $new)
                <ul class="players">
                    <li>
                        @if($new->clan)<a href="{{ $new->clan->getLink() }}">{{ $new->clan->name }}@endif <a href="{{ $new->getLink() }}">{{ $new->name }}</a>
                            ({{ $new->total_kills }})
                    </li>
                </ul>
            @endforeach
        </div>
    </div>

    <div class="right">
        <div class="darkbg">
            <a href="{{ $mostDeaths->first()->getLink() }}">
                <img src="{{ $mostDeaths->first()->getAvatarUrl() }}" class="avatar" style="width: 70px; height: 70px;">
            </a>
            <h3>Most deaths:</h3>
            @foreach($mostDeaths as $new)
                <ul class="players">
                    <li>
                        @if($new->clan)<a href="{{ $new->clan->getLink() }}">{{ $new->clan->name }}@endif <a href="{{ $new->getLink() }}">{{ $new->name }}</a>
                            ({{ $new->total_deaths }})
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
@endsection