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
        $user = $this->claim->user;

        $this->claim->player()->update([
            'user_id' => $user->id,
        ]);

        $user->notify(new ClaimApprovedNotification($this->claim));

        $this->claim->delete();
    }
}
