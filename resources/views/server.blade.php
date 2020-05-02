@extends('layouts.app')

@section('title')
    {{ $server->name }}
@endsection

@section('content')
    @if($server->wasSeenRecently())
        @php $match = $server->matches->shift() @endphp
        <p><strong>Currently playing <u>{{ $match->map }}</u></strong></p>
        <p>since {{ $match->created_at->format('Y-m-d') }} at {{ $match->created_at->format('H:i') }} ({{ $match->lengthForHumans() }})</p>

        <table align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clan</th>
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
                <td>{{ $player->pivot->team }}</td>
                <td>{{ $player->formatValueHtml($player->pivot->score)  }}</td>
                <td>{{ $player->formatValueHtml($player->pivot->kills)  }}</td>
                <td>{{ $player->formatValueHtml($player->pivot->deaths)  }}</td>
                <td>{{ $player->inGameTime()  }}</td>
            </tr>
        @endforeach

            </tbody>
        </table>
    @endif

    <p>&nbsp;</p>
    <p><strong>Previous matches on {{ $server->name }}</strong></p>

    <table align="center">
    <thead>
    <tr>
        <th>#</th>
        <th>Map</th>
        <th>Team 1</th>
        <th>Team 2</th>
        <th>Date</th>
        <th>Time</th>
    </tr>
    </thead>
    <tbody>
    <?php $nr = 1; ?>
    @foreach($server->matches as $match)
        <tr>
            <td>{{ $nr++ }}</td>
            <td class="nowrap"><a href="{{ $match->getLink() }}">{{ $match->map }}</a></td>
            <td>{{ $match->team1_name }}</td>
            <td>{{ $match->team2_name }}</td>
            <td class="nowrap">{{ $match->created_at->format('Y-m-d') }}</td>
            <td class="nowrap">{{ $match->created_at->format('H:i') }} to {{ $match->updated_at->format('H:i') }} <span class="smalltext">({{ $match->lengthForHumans() }})</span></td>

        </tr>
    @endforeach

    </tbody>
    </table>

@endsection

@section('right')
    @if(filter_var($server->server_logo, FILTER_VALIDATE_URL))
        <br />
        @if(filter_var($server->community_website, FILTER_VALIDATE_URL))
            <p><a href="{{ $server->community_website }}" target="_blank"><img src="{{ $server->server_logo }}" alt="{{ $server->name }} logo" onerror="$(this).hide()" class="server-logo" /></a></p>
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

    @if($server->wasSeenRecently())
        <h3>Currently playing</h3>
        <p>
            <img src="{{ $server->getLastMapImageUrl() }}" class="pr-map" alt="{{ $server->last_map }}" title="{{ $server->last_map }}"><br />
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
        Total score: <strong>{!! $server->formatScoreHtml('total_score') !!}</strong><br />
        Total kills: <strong>{!! $server->formatScoreHtml('total_kills') !!}</strong><br />
        Total deaths: <strong>{!! $server->formatScoreHtml('total_deaths') !!}</strong><br />
        @if(filter_var($server->br_download, FILTER_VALIDATE_URL))
        Battle records: <a href="{{ $server->br_download }}" target="_blank">{{ $server->br_download }}</a><br />
        @endif
        First seen <abbr title="{{ $server->created_at->format('Y-m-d') }}"><strong>{{ $server->created_at->diffForHumans() }}</strong></abbr><br />
        @if(!$server->wasSeenRecently())
        Last seen <abbr title="{{ $server->updated_at->format('Y-m-d') }}"><strong>{{ $server->updated_at->diffForHumans() }}</strong></abbr> playing map <strong>{{ $server->last_map }}</strong>
        @endif
    </p>
@endsection