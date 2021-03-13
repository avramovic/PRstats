<?php

namespace PRStats\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PRStats\Models\Map;

class DownloadMapImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Map
     */
    private $map;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!Storage::exists($this->map->getBannerImagePath())) {
            try {
                Storage::put($this->map->getBannerImagePath(), file_get_contents($this->map->getOriginalMapImageUrl('banner')));
            } catch (\Exception $e) {

            }
        }

        if (!Storage::exists($this->map->getTileImagePath())) {
            try {
                Storage::put($this->map->getTileImagePath(), file_get_contents($this->map->getOriginalMapImageUrl()));
            } catch (\Exception $e) {

            }
        }
    }
}
