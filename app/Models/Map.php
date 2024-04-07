<?php

namespace PRStats\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Map extends Model
{
    protected $guarded = [];

    public function matches()
    {
        return $this->hasMany(Round::class);
    }

    protected function getMapImageName($stripBeta = false)
    {
        $niceName = strtolower(str_replace([' ', "'"], '', $this->name));

        if ($stripBeta) {
            return str_replace('-beta', '', $niceName);
        }

        return $niceName;
    }

    public function getOriginalMapImageUrl($image = 'tile', $stripBeta = false)
    {
        return 'https://www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName($stripBeta).'/'.$image.'.jpg';
    }

    public function getMapLayoutImageUrl()
    {
        return 'https://raw.githubusercontent.com/yossizap/realitytracker/master/Maps/'.strtolower(str_replace([' ', "'"], ['_', ''], $this->name)).'.png';
    }

    public function getBannerImagePath()
    {
        return 'maps' . DIRECTORY_SEPARATOR
            . $this->slug . DIRECTORY_SEPARATOR
            . 'banner.png';
    }

    public function getTileImagePath()
    {
        return 'maps' . DIRECTORY_SEPARATOR
            . $this->slug . DIRECTORY_SEPARATOR
            . 'tile.png';
    }

    public function getLink()
    {
        $slug = Str::slug($this->name);
        return route('map', [$this->id, $slug]);
    }

    public function dailyActivity($days = 7)
    {
        return \Cache::remember('map_daily_'.$this->id, 3600, function () use ($days) {
            $stats = \DB::table('matches')
                ->select(\DB::raw('count(*) as match_cnt, date(updated_at) as date'))
                ->where('map_id', $this->id)
                ->groupBy(\DB::raw('YEAR(updated_at), MONTH(updated_at), DAYOFMONTH(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($days)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                $data[$stat->date] = $stat->match_cnt;
            }

            $result = [];
            $start  = Carbon::now()->endOfDay();
            $end    = Carbon::now()->subDays($days - 1);

            for ($date = $end->copy(); $date->lte($start); $date = $date->copy()->addDay()) {
                $day          = (string)$date->toDateString();
                $result[$day] = isset($data[$day]) ? (int)$data[$day] : 0;
            }

            return $result;
        });
    }

    public function weeklyActivity($weeks = 12)
    {
        return \Cache::remember('map_weekly_'.$this->id, 3600 * 4, function () use ($weeks) {
            $stats = \DB::table('matches')
                ->select(\DB::raw('count(*) as match_cnt, updated_at, WEEKOFYEAR(updated_at) as woy'))
                ->where('map_id', $this->id)
                ->groupBy(\DB::raw('YEAR(updated_at), WEEKOFYEAR(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($weeks + 1)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                if (Carbon::parse($stat->updated_at)->lt(Carbon::now()->subWeeks($weeks))) {
                    continue;
                }
                $data[$stat->woy] = $stat->match_cnt;
            }

            $result = [];
            $start  = Carbon::now()->endOfDay();
            $end    = Carbon::now()->subWeeks($weeks - 1);

            for ($date = $end->copy(); $date->lte($start); $date = $date->copy()->addWeek()) {
                $week          = (int)$date->format('W');
                $result[$week] = isset($data[$week]) ? (int)$data[$week] : 0;
            }

            return $result;
        });
    }

}
