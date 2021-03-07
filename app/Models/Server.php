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

    protected $guarded = ['id'];

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
        $end    = Carbon::now()->subDays($days-1);

        for ($date = $end->copy(); $date->lte($start); $date=$date->copy()->addDay()) {
            $m = (string)$date->toDateString();
            $result[$m] = isset($data[$m]) ? (int)$data[$m] : 0;
        }

        return ($result);
    }


    public function monthlyActivity($months = 12)
    {
        $stats = \DB::table('match_player')
            ->select(\DB::raw('count(distinct player_id) as plr_cnt, month(updated_at) as date'))
            ->whereIn('match_id', function ($q) use ($months) {
                $q->select('id')
                    ->from('matches')
                    ->where('server_id', $this->id)
                    ->where('created_at', '>=', Carbon::now()->subMonths($months));
            })
            ->groupBy(\DB::raw('YEAR(updated_at), MONTH(updated_at)'))
            ->orderBy('updated_at', 'desc')
            ->limit($months)
            ->get();

        $data = [];

        foreach ($stats as $stat) {
            $data[$stat->date] = $stat->plr_cnt;
        }

        $result = [];
        $start  = Carbon::now()->endOfDay();
        $end    = Carbon::now()->subMonths($months-1);

        for ($date = $end->copy(); $date->lte($start); $date=$date->copy()->addMonth()) {
            $m = (string)$date->format('n');
            $result[$m] = isset($data[$m]) ? (int)$data[$m] : 0;
        }

        return ($result);
    }
}
