<div class="col-md-12 mb">
    <div class="message-p pn">
        <div class="message-header">
            <h5>EDIT USER PROFILE</h5>
        </div>
        <div class="row">
            <div class="col-md-12">
               @if($user->is_admin)
                   <p>This user profile is set to be ADMIN!</p>
                   @else
                    <p>This user profile is set to be USER!</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /Message Panel-->
</div>