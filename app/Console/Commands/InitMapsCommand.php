<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use PRStats\Jobs\DownloadMapImagesJob;
use PRStats\Models\Map;

class InitMapsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:maps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize maps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $uniqueMaps = \DB::table('matches')
//            ->selectRaw('distinct map')
//            ->get();
//
//        foreach ($uniqueMaps as $uniqueMap) {
//            $map = Map::where('name', $uniqueMap->map)->first();
//
//            if (!$map) {
//                $this->line(sprintf('Creating map %s', $uniqueMap->map));
//                $map = Map::create([
//                    'name' => $uniqueMap->map,
//                    'slug' => Str::slug($uniqueMap->map),
//                ]);
//            } else {
//                $this->line(sprintf('Map %s exists', $map->name));
//            }
//
//            dispatch(new DownloadMapImagesJob($map));
//
//            \DB::table('matches')
//                ->where('map', $map->name)
//                ->update(['map_id' => $map->id]);
//        }

        foreach (Map::all() as $map) {
            $first           = $map->matches()->orderBy('id')->first();
            $last            = $map->matches()->orderBy('id', 'desc')->first();
            $map->timestamps = false;
            $map->update([
                'created_at' => $first->created_at,
                'updated_at' => $last->updated_at,
            ], ['timestamps' => 'false']);

            dispatch(new DownloadMapImagesJob($map));
        }

        $this->info('Done');
    }
}
