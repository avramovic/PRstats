<?php

namespace PRStats\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\HasCountryFlag;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Server extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait, HasCountryFlag;

    protected $guarded = [];

    public function getLink()
    {
        $slug = Str::slug($this->name);
        return route('server', [$this->id, $slug]);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function getMapImageName()
    {
        return strtolower(str_replace(' ', '', $this->last_map));
    }

    public function getLastMapImageUrl($image = 'tile')
    {
        return '//www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/'.$image.'.jpg';
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function lastMatch()
    {
        return $this->matches->sortByDesc('id')->first();
    }

    public function dailyActivity($days = 7)
    {
        return \Cache::remember('server_daily_'.$this->id, 3600, function () use ($days) {
            $stats = \DB::table('match_player')
                ->select(\DB::raw('count(distinct player_id) as plr_cnt, date(updated_at) as date'))
                ->whereIn('match_id', function ($q) use ($days) {
                    $q->select('id')
                        ->from('matches')
                        ->where('server_id', $this->id)
                        ->where('created_at', '>=', Carbon::now()->subDays($days));
                })
                ->groupBy(\DB::raw('YEAR(updated_at), MONTH(updated_at), DAYOFMONTH(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($days)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                $data[$stat->date] = $stat->plr_cnt;
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
        return \Cache::remember('server_weekly_'.$this->id, 3600 * 4, function () use ($weeks) {
            $stats = \DB::table('match_player')
                ->select(\DB::raw('count(distinct player_id) as plr_cnt, YEAR(updated_at) as year, WEEKOFYEAR(updated_at) as woy'))
                ->whereIn('match_id', function ($q) use ($weeks) {
                    $q->select('id')
                        ->from('matches')
                        ->where('server_id', $this->id)
                        ->where('created_at', '>=', Carbon::now()->subWeeks($weeks));
                })
                ->groupBy(\DB::raw('YEAR(updated_at), WEEKOFYEAR(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($weeks + 1)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                if ($stat->year != date('Y')) {
                    continue;
                }
                $data[$stat->woy] = $stat->plr_cnt;
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

    public function hourlyActivity($hours = 24)
    {
        return \Cache::remember('server_hourly_'.$this->id.'_'.(int)$this->wasSeenRecently(), 3600 * 24 * 7, function () use ($hours) {
            $stats = \DB::table('match_player')
                ->select(\DB::raw('(count(distinct player_id)/count(distinct match_id)) as plr_cnt, HOUR(updated_at) as woy'))
                ->whereIn('match_id', function ($q) use ($hours) {
                    $q->select('id')
                        ->from('matches')
                        ->where('server_id', $this->id)
                        ->when($this->wasSeenRecently(), function ($q) {
                            $q->where('created_at', '>=', Carbon::now()->subMonth());
                        });
                })
                ->groupBy(\DB::raw('HOUR(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($hours + 1)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                $data[(int)$stat->woy] = $stat->plr_cnt;
            }

            $result = [];

            for ($hour = 0; $hour < $hours; $hour++) {
                $result[$hour] = isset($data[$hour]) ? (int)$data[$hour] : 0;
            }

            return $result;
        });
    }

    public function playerCount()
    {
        return \Cache::remember('player_count_'.$this->id, 3600, function () {
            return \DB::table('match_player')
                ->select(\DB::raw('count(distinct player_id) as plr_cnt'))
                ->whereIn('match_id', function ($q) {
                    $q->select('id')
                        ->from('matches')
                        ->where('server_id', $this->id);
                })
                ->first()->plr_cnt;
        });

    }
}
