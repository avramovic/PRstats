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
                <th>Total score</th>
                <th>Total kills</th>
                <th>Total deaths</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @foreach($servers as $server)
        <tr>
            <td>{{ $nr++ }}</td>
            <td><a href="{{ $server->getLink() }}">{{ $server->name }}</a></td>
            <td>{{ $server->total_score }}</td>
            <td>{{ $server->total_kills }}</td>
            <td>{{ $server->total_deaths }}</td>
        </tr>
    @endforeach

        </tbody>
    </table>
@endsection

@section('right')
    <h2>Top active servers</h2>
    <p>Here you can see top servers which were active in the past 24 hours.</p>
@endsection