<?php

namespace PRStats\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PRStats\Models\User;
use PRStats\Notifications\ProfileCreatedNotification;

class AsyncFetchProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $profile = $this->getGuzzleRequest('https://www.gravatar.com/'.md5(strtolower($this->user->email)).'.json');
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage(), $exception->getTrace());
            $profile = null;
        }

        if ($profile) {
            $this->user->update([
                'name'     => $profile['entry'][0]['displayName'] ?? $this->user->name,
                'bio'      => $profile['entry'][0]['aboutMe'] ?? $this->user->bio,
                'location' => $profile['entry'][0]['currentLocation'] ?? $this->user->location,
            ]);
        }
    }

    protected function getGuzzleRequest($url)
    {
        $client   = new \GuzzleHttp\Client();
        $request  = $client->get($url);
        $response = $request->getBody()->getContents();

        return json_decode($response, true);

    }
}
