<div class="row mt content-panel">
    <div class="col-lg-12">
        <div class="">
            <h4><i class="fa fa-angle-right"></i> Previous matches of {{ $map->name }}</h4>
            <section id="unseen">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Server</th>
                        <th class="hidden-sm hidden-xs">Team 1</th>
                        <th class="hidden-sm hidden-xs">Team 2</th>
                        <th>Players</th>
                        <th>Started at</th>
                        <th class="hidden-sm hidden-xs">Ended at</th>
                        <th>Duration</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($matches as $match)
                        <tr>
                            <td>{{ $matches->perPage()*($matches->currentPage()-1)+$loop->iteration }}</td>
                            <td><a href="{{ $match->getLink() }}">{{ $match->server->name }}</a></td>
                            <td class="hidden-sm hidden-xs">{{ $match->team1_name }}</td>
                            <td class="hidden-sm hidden-xs">{{ $match->team2_name }}</td>
                            <td class="numeric">{{ $match->players_count }}</td>
                            <td>{{ $match->created_at->toDateTimeString() }}</td>
                            @if($match->wasSeenRecently())
                                <td class="hidden-sm hidden-xs"><em>still playing</em></td>
                            @else
                                <td class="hidden-sm hidden-xs">{{ $match->updated_at->toDateTimeString() }}</td>
                            @endif
                            <td>{{ $match->lengthForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No matches found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="centered">
                    {!! $matches->links() !!}
                </div>
            </section>
        </div>
        <!-- /content-panel -->
    </div>
</div>
