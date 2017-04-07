@extends('layouts.app')

@section('title')
    @if(isset($query))
        Search results
    @else
        Top clans of all times
    @endif
@endsection

@section('content')
    <table align="center">
        <thead>
            <tr>
                <th>#</th>
                <th>Clan</th>
                <th>Top player</th>
                <th>Clan score</th>
                <th>Clan kills</th>
                <th>Clan deaths</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @foreach($clans as $clan)
        <tr>
            <td>{{ $nr++ }}</td>
            <td><a href="{{ $clan->getLink() }}">{{ $clan->name }}</a></td>
            <td>
                @if($clan->leader)
                    <span class="clan"><a href="{{ $clan->leader->getLink() }}">{{ $clan->leader->name }}</a></span>
                @else
                    &mdash;
                @endif
            </td>
            <td>{{ $clan->total_score }}</td>
            <td>{{ $clan->total_kills }}</td>
            <td>{{ $clan->total_deaths }}</td>
        </tr>
    @endforeach

        </tbody>
    </table>
@endsection

@section('right')
    <h2>Clan search</h2>
    {!! \Form::open(['route' => 'clans.search', 'method' => 'post']) !!}
    {!! csrf_field() !!}
    <input type="text" name="q" minlength="2" required value="{{ isset($query) ? $query : '' }}"/>
    {!! \Form::submit('Search') !!}
    {!! Form::close() !!}
@endsection