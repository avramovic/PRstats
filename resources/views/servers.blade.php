@extends('layouts.app')

@section('title')
    @if(isset($query))
        Search results
    @else
        Top active servers
    @endif
@endsection

@section('content')
    <table align="center">
        <thead>
            <tr>
                <th>#</th>
                <th>Server name</th>
                <th>Country</th>
                <th>Total score</th>
                <th>Total kills</th>
                <th>Total deaths</th>
                <th>Matches</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    <?php /** @var $server \PRStats\Models\Server */ ?>
    @forelse($servers as $server)
        <tr>
            <td>{{ $nr++ }}</td>
            <td><a href="{{ $server->getLink() }}">{{ $server->name }}</a></td>
            <td><img src="https://www.countryflags.io/{{ strtolower($server->country) }}/shiny/24.png" alt="{{ $server->country }}" title="{{ $server->country }}" /></td>
            <td>{!! $server->formatScoreHtml('total_score') !!}</td>
            <td>{!! $server->formatScoreHtml('total_kills') !!}</td>
            <td>{!! $server->formatScoreHtml('total_deaths') !!}</td>
            <td>{!! $server->matches_count !!}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5">Nothing found</td>
        </tr>
    @endforelse

        </tbody>
    </table>
@endsection

@section('right')
    <h2>Top active servers</h2>
    <p>Here you can see top servers which were active in the past 24 hours.</p>
@endsection