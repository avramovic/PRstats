@extends('layouts.app')

@section('title')
    Top players in {{ date('F') }}
@endsection

@section('content')
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
            <td>{{ $player->monthly_score }}</td>
            <td>{{ $player->monthly_kills }}</td>
            <td>{{ $player->monthly_deaths }}</td>
        </tr>
    @endforeach

        </tbody>
    </table>
@endsection

@section('right')
    <h2>Welcome!</h2>
    <p>PR stats is a website which tracks your <a href="http://www.realitymod.com" target="_blank">Project Reality</a> stats in real-time. The table on the left shows top 50 players
        which have been playing in the last month and their accumulated score(s) since they have first been seen playing.</p>
    <p>Due to PR being a frankenstein of a game, your stats won't count until you are in the top 64 players on the server.
        Consider that a feature, a nice way of filtering AFKers and lousy players :-)</p>
@endsection