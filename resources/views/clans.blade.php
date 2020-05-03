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
                <th></th>
                <th>Clan</th>
                <th></th>
                <th>Top player</th>
                <th>Members</th>
                <th>Clan score</th>
                <th>Clan kills</th>
                <th>Clan deaths</th>
            </tr>
        </thead>
        <tbody>
    <?php $nr = 1; ?>
    @forelse($clans as $clan)
        <tr>
            <td>{{ $nr++ }}</td>
            <td>{!! $clan->getCountryFlagHtml() !!}</td>
            <td><a href="{{ $clan->getLink() }}">{{ $clan->name }}</a></td>
            <td>{!! $clan->leader ? $clan->leader->getCountryFlagHtml() : '' !!}</td>
            <td>
                @if($clan->leader)
                    <span class="clan"><a href="{{ $clan->leader->getLink() }}">{{ $clan->leader->name }}</a></span>
                @else
                    &mdash;
                @endif
            </td>
            <td>{!! $clan->players_count !!}</td>
            <td>{!! $clan->formatScoreHtml('total_score') !!}</td>
            <td>{!! $clan->formatScoreHtml('total_kills') !!}</td>
            <td>{!! $clan->formatScoreHtml('total_deaths') !!}</td>
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
    <h2>Clan search</h2>
    {!! \Form::open(['route' => 'clans.search', 'method' => 'post']) !!}
    {!! csrf_field() !!}
    <input type="text" name="q" minlength="2" maxlength="6" required value="{{ isset($query) ? $query : '' }}"/>
    {!! \Form::submit('Search') !!}
    {!! Form::close() !!}
@endsection