<?php

namespace PRStats\Jobs;

use Avram\Robohash\Robohash;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic as Image;
use PRStats\Models\Map;
use PRStats\Models\Player;
use Storage;

class MakePlayerSignatureJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Player
     */
    private $player;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastMatch = $this->player->matches()
            ->with(['server', 'map'])
            ->orderBy('id', 'desc')
            ->first();

        if ($this->player->wasSeenRecently()) {
            $lastSeenText = 'currently playing '.$lastMatch->map->name;
        } else {
            $lastSeenText = 'last seen on '.$this->player->updated_at->toDateString().
                ' at '.$this->player->updated_at->format('H:i') . ' playing '.$lastMatch->map->name;
        }

        //make signature
        $signature = Image::canvas(490, 160);

        $cover  = $this->getMapImage();
        $avatar = $this->getAvatarImage();
        $logo   = Image::make(public_path('img/logo.png'))->resize(100, 100);

        $signature->insert($cover, 'top');

        $signature->rectangle(20, 20, 470, 115, function ($draw) {
            $draw->background('rgba(102, 102, 102, 0.33)');
            $draw->border(1, '#000');
        });

        $signature->insert($avatar, 'right', 10, 500);
        $signature->insert($logo, 'bottom-left');

        $signature->text($this->player->full_name, 30, 43, function ($font) {
            $font->file(public_path('fonts/roboto/Roboto-Bold.ttf'));
            $font->size(20);
            $font->color('#fff');
        });

        $signature->text($lastSeenText, 30, 59, function ($font) {
            $font->file(public_path('fonts/roboto/Roboto-Regular.ttf'));
            $font->size(10);
            $font->color('#fff');
        });
        $signature->text(' on '.$lastMatch->server->name, 100, 71, function ($font) {
            $font->file(public_path('fonts/roboto/Roboto-Regular.ttf'));
            $font->size(10);
            $font->color('#fff');
        });

        $signature->text(vsprintf('Total score: %s | Kills: %s | Deaths: %s', [
            $this->player->formatScore('total_score'),
            $this->player->formatScore('total_kills'),
            $this->player->formatScore('total_deaths'),
        ]), 110, 95, function ($font) {
            $font->file(public_path('fonts/roboto/Roboto-Bold.ttf'));
            $font->size(12);
            $font->color('#fff');
        });
        $signature->text(vsprintf('K/D ratio: %s | Total playtime: ~%s', [
            $this->player->total_deaths == 0 ? $this->player->total_kills : round($this->player->total_kills/$this->player->total_deaths, 2),
            \Carbon\Carbon::now()->addMinutes($this->player->minutes_played)->diffForHumans(null, true)
        ]), 110, 110, function ($font) {
            $font->file(public_path('fonts/roboto/Roboto-Bold.ttf'));
            $font->size(12);
            $font->color('#fff');
        });

        $signature->text('prstats.tk', 420, 126, function ($font) {
            $font->color('#fff');
        });

        Storage::put($this->player->getSignaturePath(), $signature->encode('png'));
        \Log::info(sprintf('Created signature for player %s (%s)', $this->player->full_name, $this->player->getSignaturePath()));

        if ($this->player->wasSeenRecently(10)) {
            dispatch(with(new static($this->player))->delay(Carbon::now()->addMinutes(10)));
        }
    }

    protected function getMapImage()
    {
        $lastMatch = $this->player->matches()
            ->with(['map'])
            ->orderBy('id', 'desc')
            ->first();

        $map = $lastMatch ? $lastMatch->map : null;

        if (!$lastMatch || !$map || !Storage::disk('s3')->exists($lastMatch->map->getBannerImagePath())) {
            $map = Map::all()->random();

            while (!Storage::disk('s3')->exists($map->getBannerImagePath())) {
                $map = Map::all()->random();
            }
        }

//        return \Cache::remember('banner-'.$map->slug, 3600 * 4, function () use ($map) {
        return Image::make(Storage::disk('s3')->get($map->getBannerImagePath()))
            ->resize(490, 135);
//        });
    }

    protected function getAvatarImage()
    {
//        return \Cache::remember('avatar-'.$this->player->pid, 3600, function () {
        if (Storage::disk('s3')->exists($this->player->getAvatarPath())) {
            return Image::make(Storage::disk('s3')->get($this->player->getAvatarPath()))
                ->resize(70, 70);
        }

        dispatch(new MakePlayerAvatarJob($this->player));

        return Robohash::make($this->player->pid, 70, 70, 'set5');
//        });
    }
}
