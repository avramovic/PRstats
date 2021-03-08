<div class="col-lg-6">
    <h4>{{ $slot }}</h4>
    <section id="unseen">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Clan</th>
                <th>Members</th>
                <th class="numeric">Score</th>
                <th class="numeric">Kills</th>
                <th class="numeric">Deaths</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clans as $clan)
            <tr>
                <td>{{ $clans->perPage()*($clans->currentPage()-1)+$loop->iteration }}</td>
                <td>
                    <a href="{{ $clan->getLink() }}">{{ $clan->name }}</a>
                </td>
                <td>{!! $clan->players_count !!}</td>
                <td>{!! $clan->formatScoreHtml('total_score') !!}</td>
                <td>{!! $clan->formatScoreHtml('total_kills') !!}</td>
                <td>{!! $clan->formatScoreHtml('total_deaths') !!}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="centered">
            {!! $clans->links() !!}
        </div>
    </section>
<!-- /content-panel -->
</div>
