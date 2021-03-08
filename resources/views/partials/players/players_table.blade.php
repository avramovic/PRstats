<div class="col-lg-6">
    <h4>{{ $slot }}</h4>
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
            @foreach($players as $player)
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
            @endforeach
            </tbody>
        </table>
        <div class="centered">
            {!! $players->links() !!}
        </div>
    </section>
<!-- /content-panel -->
</div>
