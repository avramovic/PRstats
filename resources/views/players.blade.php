@extends('layouts.app')

@section('title')
    @if(isset($query))
        Search results
    @else
        Top players of all times
    @endif
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
                <th>Matches</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @forelse($players as $player)
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
            <td>{!! $player->formatScoreHtml('total_score') !!}</td>
            <td>{!! $player->formatScoreHtml('total_kills') !!}</td>
            <td>{!! $player->formatScoreHtml('total_deaths') !!}</td>
            <td>{!! $player->matches_count !!}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">Nothing found</td>
        </tr>
    @endforelse

        </tbody>
    </table>
@endsection

@section('right')
    <h2>Player search</h2>
    {!! \Form::open(['route' => 'players.search', 'method' => 'post']) !!}
    {!! csrf_field() !!}
    <input type="text" name="q" minlength="3" required value="{{ isset($query) ? $query : '' }}"/>
    {!! \Form::submit('Search') !!}
    {!! Form::close() !!}
@endsection