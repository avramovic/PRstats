@extends('layouts.app')

@section('title')
    {{ $player->full_name }}
@endsection

@section('content')
    @if($player->clan)
        <div class="clear" xmlns="http://www.w3.org/1999/html"></div>
    <p><strong>{{ $player->name }}</strong> is a member of <a href="{{ $player->clan->getLink() }}">{{ $player->clan->name }}</a> clan, and its members are:</p>
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
    @foreach($clanPlayers as $clanPlayer)
        <tr>
            <td>{{ $nr++ }}</td>
            <td><a href="{{ $clanPlayer->getLink() }}">{{ $clanPlayer->name }}</a></td>
            <td>{{ $clanPlayer->total_score }}</td>
            <td>{{ $clanPlayer->total_kills }}</td>
            <td>{{ $clanPlayer->total_deaths }}</td>
        </tr>
    @endforeach

        </tbody>
    </table>
    @else
        <p>This player does not belong to any clan.</p>
    @endif
@endsection

@section('right')
    <h2>{{ $player->name }} stats</h2>
    <div class="clear"></div>
    <p style="float: left">
        Total score: <strong>{{ $player->total_score }}</strong><br />
        Total kills: <strong>{{ $player->total_kills }}</strong><br />
        Total deaths: <strong>{{ $player->total_deaths }}</strong><br />
        Total played: <strong>{{ Carbon\Carbon::now()->addMinutes($player->minutes_played)->diffForHumans(null, true) }}</strong><br />
        Games played: <strong>{{ $player->games_played }}</strong><br />
        First seen <abbr title="{{ $player->created_at->format('Y-m-d') }}"><strong>{{ $player->created_at->diffForHumans() }}</strong></abbr><br />
        Last seen <abbr title="{{ $player->updated_at->format('Y-m-d') }}"><strong>{{ $player->updated_at->diffForHumans() }}</strong></abbr> on
        <a href="{{ $player->server->getLink() }}">{{ $player->server->name }}</a><br />
    </p>
    <img data-hash="{{ md5($player->name) }}" src="https://vanillicon.com/{{ md5($player->name) }}.png" alt="{{ $player->name }}'s avatar" class="avatar">
    <div class="clear"></div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            cheet('u n i c o r n', function () {
                $('.avatar').each(function() {
                    var $this = $(this);
                    var hash = $this.data('hash');
                    $this.attr('src', 'http://unicornify.appspot.com/avatar/'+hash+'?s=100');
                });
            });
        });

    </script>
@endsection