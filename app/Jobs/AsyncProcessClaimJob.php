<?php

namespace PRStats\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PRStats\Models\Claim;
use PRStats\Notifications\ClaimApprovedNotification;
use PRStats\Notifications\ProfileCreatedNotification;

class AsyncProcessClaimJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Claim
     */
    private $claim;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->claim->player->update([
            'user_id' => $this->claim->user->id,
        ]);

        $this->claim->user->notify(new ClaimApprovedNotification($this->claim));

        dispatch(new MakePlayerSignatureJob($this->claim->player));

        $this->claim->delete();
    }
}
