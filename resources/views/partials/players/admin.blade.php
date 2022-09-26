<div class="col-md-12 mb">
    <div class="message-p pn">
        <div class="message-header">
            <h5>EDIT PLAYER PROFILE</h5>
        </div>
        <div class="row">
            <div class="col-md-12">
               @if($player->trashed())
                   <p>This player profile is set to be PRIVATE! Only you can see it.</p>
                   @else
                    <p>This player profile is set to be PUBLIC! Everyone can see it.</p>
                @endif
                <form method="post" action="{{ route('player.toggle', $player) }}">
                    @csrf
                    <p><button class="btn btn-danger">Toggle visibility</button></p>
                </form>
            </div>
        </div>
    </div>
    <!-- /Message Panel-->
</div>