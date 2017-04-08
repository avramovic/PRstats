@extends('layouts.app')

@section('title')
    {{ $server->name }}
@endsection

@section('content')
    <p><strong>Top players:</strong></p>

    <table align="center">
        <thead>
            <tr>
                <th>#</th>
                <th>Clan</th>
                <th>Name</th>
                <th>Total score</th>
                <th>Total kills</th>
                <th>Total deaths</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @foreach($players as $player)
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
            <td>{{ $player->total_score }}</td>
            <td>{{ $player->total_kills }}</td>
            <td>{{ $player->total_deaths }}</td>
        </tr>
    @endforeach

        </tbody>
    </table>
@endsection

@section('right')
    @if(filter_var($server->server_logo, FILTER_VALIDATE_URL))
        <br />
        @if(filter_var($server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $server->community_website }}" target="_blank"><img src="{{ $server->server_logo }}" alt="{{ $server->name }} logo" class="server-logo" /></a></p>
        @else
            <p><img src="{{ $server->server_logo }}" alt="{{ $server->name }} logo" onerror="$(this).hide()" class="server-logo" /></p>
        @endif
    @else
        @if(filter_var($server->community_website, FILTER_VALIDATE_URL))
            <h2><a href="{{ $server->community_website }}" target="_blank">{{ $server->name }}</a></h2>
        @else
            <h2>{{ $server->name }}</h2>
        @endif
    @endif

    <p>Slots (reserved): <strong>{{ $server->max_players }}</strong> (<strong>{{ $server->reserved_slots }}</strong>)</p>

    @if(!empty($server->server_text))
        <p>{!! str_replace('|', '<br />', $server->server_text) !!}</p>
    @endif

    @if($server->updated_at->diffInMinutes() < 5)
        <h3>Currently playing</h3>
        <p>
            Map: <strong>{{ $server->last_map }}</strong><br />
            Players (free): <strong>{{ $server->num_players }}</strong> (<strong>{{ ($server->max_players-$server->reserved_slots)-$server->num_players }}</strong>)<br />
            Team 1: <strong>{{ $server->team1_name }}</strong> (<abbr title="score / kills / deaths">{{ $server->team1_score }}/{{ $server->team1_kills }}/{{ $server->team1_deaths }}</abbr>)<br />
            Team 2: <strong>{{ $server->team2_name }}</strong> (<abbr title="score / kills / deaths">{{ $server->team2_score }}/{{ $server->team2_kills }}/{{ $server->team2_deaths }}</abbr>)<br />
        </p>
    @endif

    <hr />

    <p>
        Country: <strong>{{ $server->country }}</strong><br />
        Platform: <strong>{{ $server->os }}</strong><br />
        Total players: <strong>{{ $server->players->count() }}</strong><br />
        Total score: <strong>{{ $server->players->sum('total_score') }}</strong><br />
        Total kills: <strong>{{ $server->players->sum('total_kills') }}</strong><br />
        Total deaths: <strong>{{ $server->players->sum('total_deaths') }}</strong><br />
        @if(filter_var($server->br_download, FILTER_VALIDATE_URL))
        Battle records: <a href="{{ $server->br_download }}" target="_blank">{{ $server->br_download }}</a><br />
        @endif
        First seen <abbr title="{{ $server->created_at->format('Y-m-d') }}"><strong>{{ $server->created_at->diffForHumans() }}</strong></abbr><br />
        Last seen <abbr title="{{ $server->updated_at->format('Y-m-d') }}"><strong>{{ $server->updated_at->diffForHumans() }}</strong></abbr> playing map <strong>{{ $server->last_map }}</strong>
    </p>
@endsection