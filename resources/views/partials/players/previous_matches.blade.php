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
                        <th>Team 1</th>
                        <th>Team 2</th>
                        <th>Server</th>
                        <th>Started at</th>
                        <th>Ended at</th>
                        <th>Duration</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($matches as $match)
                        <tr style="@if($match->wasSeenRecently()) strong @endif">
                            <td>{{ $matches->perPage()*($matches->currentPage()-1)+$loop->iteration }}</td>
                            <td class="nowrap"><a href="{{ $match->getLink() }}">{{ $match->map }}</a></td>
                            <td>{{ $match->team1_name }}</td>
                            <td>{{ $match->team2_name }}</td>
                            <td><a href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a></td>
                            <td class="nowrap">{{ $match->pivot->created_at->toDateTimeString() }}</td>
                            <td class="nowrap">{{ $match->pivot->updated_at->toDateTimeString() }}</td>
                            <td class="nowrap">{{ $match->pivotLengthForHumans() }}</td>
                        </tr>
                    @endforeach
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
