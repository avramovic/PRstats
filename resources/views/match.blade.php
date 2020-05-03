@extends('layouts.app')

@section('title')
    {{$currentMatch->map}} / {{ $currentMatch->created_at->format('Y-m-d') }}
@endsection

@section('content')
    @if($currentMatch->wasSeenRecently())
        <p><strong>Currently playing <u>{{ $currentMatch->map }}</u> on <a href="{{ $currentMatch->server->getLink() }}">{{ $currentMatch->server->name }}</a></strong></p></p>
        <p>since {{ $currentMatch->created_at->format('Y-m-d') }} at {{ $currentMatch->created_at->format('H:i') }} ({{ $currentMatch->lengthForHumans() }})</p>
    @else
        <p><strong>Played <u>{{ $currentMatch->map }}</u> on <a href="{{ $currentMatch->server->getLink() }}">{{ $currentMatch->server->name }}</a></strong></p>
        <p>on {{ $currentMatch->created_at->format('Y-m-d') }} from {{ $currentMatch->created_at->format('H:i') }} to {{ $currentMatch->updated_at->format('H:i') }} ({{ $currentMatch->lengthForHumans() }})</p>
    @endif
        <table align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>Clan</th>
                    <th></th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Score</th>
                    <th>Kills</th>
                    <th>Deaths</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
        <?php $nr = 1; ?>
        @foreach($currentMatch->players as $player)
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
                <td>{{  $player->pivot->team  }}</td>
                <td>{{  $player->formatValueHtml($player->pivot->score)  }}</td>
                <td>{{  $player->formatValueHtml($player->pivot->kills)  }}</td>
                <td>{{  $player->formatValueHtml($player->pivot->deaths)  }}</td>
                <td>{{  $player->inGameTime()  }}</td>
            </tr>
        @endforeach

            </tbody>
        </table>

@endsection

@section('right')
    @if(filter_var($currentMatch->server->server_logo, FILTER_VALIDATE_URL))
        <br />
        @if(filter_var($currentMatch->server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $currentMatch->server->community_website }}" target="_blank"><img src="{{ $currentMatch->server->server_logo }}" alt="{{ $currentMatch->server->name }} logo" onerror="$(this).hide()" class="server-logo" /></a></p>
        @else
            <p><img src="{{ $currentMatch->server->server_logo }}" alt="{{ $currentMatch->server->name }} logo" onerror="$(this).hide()" class="server-logo" /></p>
        @endif
    @else
        @if(filter_var($currentMatch->server->community_website, FILTER_VALIDATE_URL))
            <h2><a href="{{ $currentMatch->server->community_website }}" target="_blank">{{ $currentMatch->server->name }}</a></h2>
        @else
            <h2>{{ $currentMatch->server->name }}</h2>
        @endif
    @endif

    <p>Slots (reserved): <strong>{{ $currentMatch->server->max_players }}</strong> (<strong>{{ $currentMatch->server->reserved_slots }}</strong>)</p>

    @if(!empty($currentMatch->server->server_text))
        <p>{!! str_replace('|', '<br />', $currentMatch->server->server_text) !!}</p>
    @endif

    @if($currentMatch->wasSeenRecently())
        <h3>Currently playing</h3>
    @else
        <h3>Was playing</h3>
    @endif
    <p>
        <img width="70%" height="auto" src="{{ $currentMatch->getNavigationMapImageUrl() }}" class="pr-map" alt="{{ $currentMatch->map }}" title="{{ $currentMatch->map }}"><br />
        Map: <strong>{{ $currentMatch->map }}</strong><br />
        Players: <strong>{{ $currentMatch->players->count() }}</strong><br />
        Team 1: <strong>{{ $currentMatch->team1_name }}</strong>  {{-- (<abbr title="score / kills / deaths">{{ $currentMatch->server->team1_score }}/{{ $currentMatch->server->team1_kills }}/{{ $currentMatch->server->team1_deaths }}</abbr>) --}}<br />
        Team 2: <strong>{{ $currentMatch->team2_name }}</strong> {{-- (<abbr title="score / kills / deaths">{{ $currentMatch->server->team2_score }}/{{ $currentMatch->server->team2_kills }}/{{ $currentMatch->server->team2_deaths }}</abbr>)<br /> --}}
    </p>

    <hr />

    <p>
        Country:<br /><img src="https://www.countryflags.io/{{ strtolower($currentMatch->server->country) }}/shiny/64.png" alt="{{ $currentMatch->server->country }}" title="{{ $currentMatch->server->country }}" /><br />
        Platform: <strong>{{ $currentMatch->server->os }}</strong><br />
        Total players: <strong>{{ $currentMatch->server->players->count() }}</strong><br />
        Total score: <strong>{!! $currentMatch->server->formatScoreHtml('total_score') !!}</strong><br />
        Total kills: <strong>{!! $currentMatch->server->formatScoreHtml('total_kills') !!}</strong><br />
        Total deaths: <strong>{!! $currentMatch->server->formatScoreHtml('total_deaths') !!}</strong><br />
        @if(filter_var($currentMatch->server->br_download, FILTER_VALIDATE_URL))
        Battle records: <a href="{{ $currentMatch->server->br_download }}" target="_blank">{{ $currentMatch->server->br_download }}</a><br />
        @endif
        First seen <abbr title="{{ $currentMatch->server->created_at->format('Y-m-d') }}"><strong>{{ $currentMatch->server->created_at->diffForHumans() }}</strong></abbr><br />
        @if(!$currentMatch->server->wasSeenRecently())
        Last seen <abbr title="{{ $currentMatch->server->updated_at->format('Y-m-d') }}"><strong>{{ $currentMatch->server->updated_at->diffForHumans() }}</strong></abbr> playing map <strong>{{ $currentMatch->server->last_map }}</strong>
        @endif
    </p>
@endsection