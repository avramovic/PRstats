<div class="col-md-12 mb">
    <div class="message-p pn">
        <div class="message-header">
            <h5>FORUM SIGNATURE</h5>
        </div>
        <div class="row">
            <div class="col-md-6 centered">
                <p>
                    Are you <strong>{{ $player->name }}</strong>?
                </p>
                <p class="message">Copy the bbcode below into your forum signature to show your stats to everyone!</p>
                <p><input class="form-field" readonly type="text" value='[URL="{{ $player->getLink() }}"][IMG]https://static.prstats.tk/{{ $player->getSignaturePath() }}[/IMG][/URL]' /></p>
            </div>
            <div class="col-md-6">
                <p><img class="img-responsive" src="https://static.prstats.tk/{{ $player->getSignaturePath() }}" alt=""></p>
            </div>
        </div>
    </div>
    <!-- /Message Panel-->
</div>