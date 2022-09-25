<div class="col-lg-{{ $width ?? 6 }}">
    <h3>{{ $slot }}</h3>
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
            </tr>
            </thead>
            <tbody>
            @forelse($players as $player)
            <tr>
                <td>{{ $players->perPage()*($players->currentPage()-1)+$loop->iteration }}</td>
                <td>
                    @if($player->clan)
                        <a href="{{ $player->clan->getLink() }}">{{ $player->clan->name }}</a>
                    @else
                        &mdash;
                    @endif
                </td>
                <td><a href="{{ $player->getLink() }}">{{ $player->name }}</a></td>
                <td>{!! $player->formatScoreHtml($metric.'_score') !!}</td>
                <td>{!! $player->formatScoreHtml($metric.'_kills') !!}</td>
                <td>{!! $player->formatScoreHtml($metric.'_deaths') !!}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="6">No players found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="centered">
            {!! $players->links() !!}
        </div>
    </section>
<!-- /content-panel -->
</div>
