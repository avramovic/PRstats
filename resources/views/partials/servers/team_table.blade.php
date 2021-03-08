<div class="col-lg-6">
    <h4> {{ $team->first() ? $team->first()->pivot->team : '' }}</h4>
    <section id="unseen">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Clan</th>
                <th>Player</th>
                <th class="numeric">Score</th>
                <th class="numeric">Kills</th>
                <th class="numeric">Deaths</th>
                <th class="numeric">Duration</th>
            </tr>
            </thead>
            <tbody>
            @forelse($team as $player)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($player->clan)
                        <span class="clan"><a href="{{ $player->clan->getLink() }}">{{ $player->clan_name }}</a></span>
                        @else
                        &mdash;
                    @endif
                </td>
{{--                    <td>{!! $player->getCountryFlagHtml() !!}</td>--}}
                <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
{{--                    <td>{{ $player->pivot->team }}</td>--}}
                <td>{{ $player->formatValueHtml($player->pivot->score)  }}</td>
                <td>{{ $player->formatValueHtml($player->pivot->kills)  }}</td>
                <td>{{ $player->formatValueHtml($player->pivot->deaths)  }}</td>
                <td>{{ $player->inGameTime()  }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="7">No players found.</td>
                </tr>
            @endforelse
            <tr class="strong">
                <td colspan="3">Total</td>
                <td>{{ $team->sum('pivot.score') }}</td>
                <td>{{ $team->sum('pivot.kills') }}</td>
                <td>{{ $team->sum('pivot.deaths') }}</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </section>
<!-- /content-panel -->
</div>
