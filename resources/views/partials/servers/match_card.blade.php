<div class="col-lg-4 col-md-4 col-sm-4 mb">
    <div class="steps pn">
        <label>Server: <a title="{{ $match->server->name }}" href="{{ $match->server->getLink() }}">{{ $match->server->name }}</a></label>
        <label>Started at: {{ $match->created_at->toDateTimeString() }}</label>
        <label>Ended at: {{ $match->updated_at->toDateTimeString() }}</label>
        <label>Duration: {{ $match->lengthForHumans() }}</label>
        <label>Players: {{ $match->players->count() }}</label>
    </div>
</div>