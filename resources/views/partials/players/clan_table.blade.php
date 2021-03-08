<div class="col-lg-8">
    <h4>{{ $clan->name }} members</h4>
    <section id="unseen">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Player</th>
                <th class="numeric">Total score</th>
                <th class="numeric">Total kills</th>
                <th class="numeric">Total deaths</th>
                <th class="numeric">Matches</th>
            </tr>
            </thead>
            <tbody>
            @foreach($players as $player)
            <tr>
                <td>{{ $loop->iteration }}</td>

{{--                    <td>{!! $player->getCountryFlagHtml() !!}</td>--}}
                <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
{{--                    <td>{{ $player->pivot->team }}</td>--}}
                <td class="numeric">{!! $player->formatScoreHtml('total_score') !!}</td>
                <td class="numeric">{!! $player->formatScoreHtml('total_kills') !!}</td>
                <td class="numeric">{!! $player->formatScoreHtml('total_deaths') !!}</td>
                <td class="numeric">{!! $player->matches_count !!}</td>
            </tr>
            @endforeach
            <tr class="strong">
                <td colspan="2">Total</td>
                <td>{!! $clan->formatValueHtml($clan->players->sum('total_score')) !!}</td>
                <td>{!! $clan->formatValueHtml($clan->players->sum('total_kills')) !!}</td>
                <td>{!! $clan->formatValueHtml($clan->players->sum('total_deaths')) !!}</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </section>
<!-- /content-panel -->
</div>
