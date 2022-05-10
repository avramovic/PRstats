<?php

namespace PRStats\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PRStats\Models\Claim;
use PRStats\Models\User;
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
        $user = $this->claim->user ?: User::where('email', $this->claim->email)->first();

        if (!$user) {

            try {
                $profile = $this->getGuzzleRequest('https://www.gravatar.com/'.md5($this->claim->email).'.json');
            } catch (\Exception $exception) {
                \Log::error($exception->getMessage(), $exception->getTrace());
                $profile = null;
            }

            $user = User::create([
                'email' => $this->claim->email,
                'name'  => $profile ? $profile['entry'][0]['displayName'] : $this->claim->player->name,
            ]);

            $user->notify(new ProfileCreatedNotification());
        }

        $this->claim->player()->update([
            'user_id' => $user->id,
        ]);

        $user->notify(new ClaimApprovedNotification($this->claim->id));

        $this->claim->delete();

    }

    protected function getGuzzleRequest($url)
    {
        $client   = new \GuzzleHttp\Client();
        $request  = $client->get($url);
        $response = $request->getBody()->getContents();

        return json_decode($response);

    }
}
