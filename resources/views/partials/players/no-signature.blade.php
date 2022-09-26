<div class="col-md-12 mb">
    <div class="message-p pn">
        <div class="message-header">
            <h5>FORUM SIGNATURE</h5>
        </div>
        <div class="row">
            <div class="col-md-12 centered">
                <p>
                    Are you <strong>{{ $player->name }}</strong>?
                </p>
                <p class="message">In order to get your dynamic forum signature, either reach <strong>250,000</strong> in
                    total score or <strong>claim this profile</strong>.</p>
                @if(empty($player->user_id))
                    @if(Auth::guest())
                        <a href="{{ route('claim.index') }}" class="btn btn-danger btn-lg">
                            <i class="fa fa-legal" id="claim-icon"></i> <span id="claim-label">Claim</span>
                        </a>
                    @else
                        <button type="button" class="btn btn-danger btn-lg claim-btn">
                            <i class="fa fa-legal" id="claim-icon"></i> <span id="claim-label">Claim</span>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!-- /Message Panel-->
</div>