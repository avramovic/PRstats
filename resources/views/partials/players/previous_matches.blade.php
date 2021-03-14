<div class="row mt content-panel">
    <div class="col-lg-12">
        <div class="">
            <h4><i class="fa fa-angle-right"></i> Previous matches of {{ $player->name }}</h4>
            <section id="unseen">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Map</th>
                        <th class="hidden-sm hidden-xs">Team 1</th>
                        <th class="hidden-sm hidden-xs">Team 2</th>
                        <th>Server</th>
                        <th>Started at</th>
                        <th class="hidden-sm hidden-xs">Ended at</th>
                        <th>Duration</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($matches as $match)
                        <tr style="@if($match->wasSeenRecently()) strong @endif">
                            <td>{{ $matches->perPage()*($matches->currentPage()-1)+$loop->iteration }}</td>
                            <td class="nowrap"><a href="{{ $match->getLink() }}">{{ $match->map->name }}</a></td>
                            <td class="hidden-sm hidden-xs">{{ $match->team1_name }}</td>
                            <td class="hidden-sm hidden-xs">{{ $match->team2_name }}</td>
                            <td><a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a></td>
                            <td>{{ $match->pivot->created_at->toDateTimeString() }}</td>
                            <td class="hidden-sm hidden-xs">{{ $match->pivot->updated_at->toDateTimeString() }}</td>
                            <td>{{ $match->pivotLengthForHumans() }}</td>
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
