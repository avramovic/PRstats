@extends('layouts.app')

@section('title')
    {{$match->map}} played on {{ $match->created_at->format('Y-m-d') }}
@endsection

@section('content')
    @if($match->wasSeenRecently())
        <p><strong>Currently playing {{ $match->map }}  <a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a></strong></p></p>
        <p>since {{ $match->created_at->format('Y-m-d') }} at {{ $match->created_at->format('H:i') }} ({{ $match->lengthForHumans() }})</p>
    @else
        <p><strong>Played {{ $match->map }} on <a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a></strong></p>
        <p>on {{ $match->created_at->format('Y-m-d') }} from {{ $match->created_at->format('H:i') }} to {{ $match->updated_at->format('H:i') }} ({{ $match->lengthForHumans() }})</p>
    @endif
        <table align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clan</th>
                    <th>Name</th>
                    <th>Score</th>
                    <th>Kills</th>
                    <th>Deaths</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
        <?php $nr = 1; ?>
        @foreach($match->players as $player)
            <tr>
                <td>{{ $nr++ }}</td>
                <td>
                    @if($player->clan)
                        <span class="clan"><a href="{{ $player->clan->getLink() }}">{{ $player->clan_name }}</a></span>
                    @else
                        &mdash;
                    @endif
                </td>
                <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
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
    @if(filter_var($match->server->server_logo, FILTER_VALIDATE_URL))
        <br />
        @if(filter_var($match->server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $match->server->community_website }}" target="_blank"><img src="{{ $match->server->server_logo }}" alt="{{ $match->server->name }} logo" onerror="$(this).hide()" class="server-logo" /></a></p>
        @else
            <p><img src="{{ $match->server->server_logo }}" alt="{{ $match->server->name }} logo" onerror="$(this).hide()" class="server-logo" /></p>
        @endif
    @else
        @if(filter_var($match->server->community_website, FILTER_VALIDATE_URL))
            <h2><a href="{{ $match->server->community_website }}" target="_blank">{{ $match->server->name }}</a></h2>
        @else
            <h2>{{ $match->server->name }}</h2>
        @endif
    @endif

    <p>Slots (reserved): <strong>{{ $match->server->max_players }}</strong> (<strong>{{ $match->server->reserved_slots }}</strong>)</p>

    @if(!empty($match->server->server_text))
        <p>{!! str_replace('|', '<br />', $match->server->server_text) !!}</p>
    @endif

    @if($match->server->wasSeenRecently())
        <h3>Currently playing</h3>
        <p>
            <img src="{{ $match->server->getLastMapImageUrl() }}" class="pr-map" alt="{{ $match->server->last_map }}" title="{{ $match->server->last_map }}"><br />
            Map: <strong>{{ $match->server->last_map }}</strong><br />
            Players (free): <strong>{{ $match->server->num_players }}</strong> (<strong>{{ ($match->server->max_players-$match->server->reserved_slots)-$match->server->num_players }}</strong>)<br />
            Team 1: <strong>{{ $match->server->team1_name }}</strong> (<abbr title="score / kills / deaths">{{ $match->server->team1_score }}/{{ $match->server->team1_kills }}/{{ $match->server->team1_deaths }}</abbr>)<br />
            Team 2: <strong>{{ $match->server->team2_name }}</strong> (<abbr title="score / kills / deaths">{{ $match->server->team2_score }}/{{ $match->server->team2_kills }}/{{ $match->server->team2_deaths }}</abbr>)<br />
        </p>
    @endif

    <hr />

    <p>
        Country: <strong>{{ $match->server->country }}</strong><br />
        Platform: <strong>{{ $match->server->os }}</strong><br />
        Total players: <strong>{{ $match->server->players->count() }}</strong><br />
        Total score: <strong>{!! $match->server->formatScoreHtml('total_score') !!}</strong><br />
        Total kills: <strong>{!! $match->server->formatScoreHtml('total_kills') !!}</strong><br />
        Total deaths: <strong>{!! $match->server->formatScoreHtml('total_deaths') !!}</strong><br />
        @if(filter_var($match->server->br_download, FILTER_VALIDATE_URL))
        Battle records: <a href="{{ $match->server->br_download }}" target="_blank">{{ $match->server->br_download }}</a><br />
        @endif
        First seen <abbr title="{{ $match->server->created_at->format('Y-m-d') }}"><strong>{{ $match->server->created_at->diffForHumans() }}</strong></abbr><br />
        @if(!$match->server->wasSeenRecently())
        Last seen <abbr title="{{ $match->server->updated_at->format('Y-m-d') }}"><strong>{{ $match->server->updated_at->diffForHumans() }}</strong></abbr> playing map <strong>{{ $match->server->last_map }}</strong>
        @endif
    </p>
@endsection