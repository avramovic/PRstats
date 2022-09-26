@extends('layouts.prstats')

@section('title')
    {{ $user->name }}
@endsection

@section('subtitle')
    User: {{ $user->name }}
@endsection

@section('content')
    @if(!Auth::guest() && ((Auth::user()->id == $user->id) || Auth::user()->is_admin))
        @include('partials.users.admin')
    @endif

    <div class="col-lg-6">
        <div class="profile-pic" style="float:right;">
            <img style="margin-top:0!important;" src="{!! $user->getAvatarUrl() !!}" />
        </div>
        <p>&nbsp;</p>
        <p>Member since <abbr title="{{ $user->created_at->format('Y-m-d') }}">{{ $user->created_at->diffForHumans()}}</abbr></p>
        @if($user->location)<h5>Location: {{ $user->location }}</h5>@endif
        @if($user->bio)<p>{{ $user->bio }}</p>@endif
        <div id="subscriptions">
            @include('partials.players.players_table', [
                'width'   => '12',
                'slot'    => 'Claimed player profiles',
                'metric'  => 'created_at',
                'players' => $user->players,
            ])
        </div>


    </div>


@endsection

@section('scripts')
@endsection