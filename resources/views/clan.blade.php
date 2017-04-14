@extends('layouts.app')

@section('title')
    {{ $clan->name }} clan
@endsection

@section('content')
    <table align="center">
        <thead>
            <tr>
                <th>#</th>
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
    <h2>{{ $clan->name }} clan stats</h2>
    <div class="clear"></div>
    <p>
        <img data-hash="{{ md5($clan->name) }}" src="https://vanillicon.com/{{ md5($clan->name) }}.png" alt="{{ $clan->name }}'s avatar" class="avatar">
        Members: <strong>{{ $clan->players->count() }}</strong><br />
        Total score: <strong>{{ $clan->total_score }}</strong><br />
        Total kills: <strong>{{ $clan->total_kills }}</strong><br />
        Total deaths: <strong>{{ $clan->total_deaths }}</strong><br />
        K/D ratio: <strong>{{ $clan->total_deaths == 0 ? $clan->total_kills : round($clan->total_kills/$clan->total_deaths, 2) }}</strong><br />
        First seen <abbr title="{{ $clan->created_at->format('Y-m-d') }}"><strong>{{ $clan->created_at->diffForHumans() }}</strong></abbr><br />
        @if($clan->last_player_seen)
            Last seen <abbr title="{{ $clan->last_player_seen->updated_at->format('Y-m-d') }}"><strong>{{ $clan->last_player_seen->updated_at->diffForHumans() }}</strong></abbr> on
            <a href="{{ $clan->last_player_seen->server->getLink() }}">{{ $clan->last_player_seen->server->name }}</a><br />
        @endif
    </p>
    <div class="clear"></div>
@endsection