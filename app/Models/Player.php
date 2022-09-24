<?php

namespace PRStats\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\HasCountryFlag;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Player extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait, HasCountryFlag, Notifiable;

    protected $guarded = ['id'];

    public function getLink()
    {
        $slug = empty($this->slug) ? 'player' : $this->slug;
        return route('player', [$this->id, $slug]);
    }

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getClanNameAttribute()
    {
        return $this->clan->name;
    }

    public function getFullNameAttribute()
    {
        if (empty($this->clan_id)) {
            return $this->name;
        }

        return $this->getClanNameAttribute().' '.$this->name;
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->withTimestamps()->withPivot(['score', 'kills', 'deaths', 'team']);
    }

    public function getAvatarUrl($size = 140)
    {
        return 'https://static.prstats.tk/'.$this->getAvatarPath();
//        return vsprintf('https://robohash.org/%s.png?set=set5&size=%dx%d', [
//            md5($this->name),
//            $size,
//            $size,
//        ]);
    }

    public function minutesPlayed()
    {
        return $this->matches->reduce(function ($carry, $entry) {
            return $carry + $entry->pivot->updated_at->diffInMinutes($entry->pivot->created_at);
        }, 0);
    }

    public function dailyActivity($days = 7)
    {
        return \Cache::remember('player_daily_'.$this->id, 3600, function () use ($days) {
            $stats = \DB::table('match_player')
                ->select(\DB::raw('count(*) as cnt, date(updated_at) as date'))
                ->where('player_id', $this->id)
                ->groupBy(\DB::raw('YEAR(updated_at), MONTH(updated_at), DAYOFMONTH(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($days)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                $data[$stat->date] = $stat->cnt;
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
        return \Cache::remember('player_weekly_'.$this->id, 3600 * 4, function () use ($weeks) {
            $stats = \DB::table('match_player')
                ->select(\DB::raw('count(*) as cnt, updated_at, WEEKOFYEAR(updated_at) as woy'))
                ->where('player_id', $this->id)
                ->groupBy(\DB::raw('YEAR(updated_at), WEEKOFYEAR(updated_at)'))
                ->orderBy('updated_at', 'desc')
                ->limit($weeks + 1)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                if (Carbon::parse($stat->updated_at)->lt(Carbon::now()->subWeeks($weeks))) {
                    continue;
                }
                $data[$stat->woy] = $stat->cnt;
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

    public function getAvatarPath()
    {
        return 'avatars' . DIRECTORY_SEPARATOR
            . substr($this->pid, 0, 2) . DIRECTORY_SEPARATOR
            . substr($this->pid, 2, 2) . DIRECTORY_SEPARATOR
            . $this->pid . '.png';
    }

    public function getSignaturePath()
    {
        return 'signatures' . DIRECTORY_SEPARATOR
            . substr($this->pid, 0, 2) . DIRECTORY_SEPARATOR
            . substr($this->pid, 2, 2) . DIRECTORY_SEPARATOR
            . $this->pid . '.png';
    }

    public function routeNotificationForOneSignal()
    {
        $subscriptions = $this->subscriptions()
            ->whereNotNull('approved_at')
            ->with(['device'])
            ->get();

        return $subscriptions->pluck('device.uuid');
    }

}
