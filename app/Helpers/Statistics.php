<?php namespace PRStats\Helpers;

use Carbon\Carbon;

class Statistics
{

    public static function dailyTrends($table, $field = 'created_at', $days = 7)
    {
        return \Cache::remember('daily_'.$table.'_'.$field.'_'.$days, 300, function () use ($table, $field, $days) {
            $stats = \DB::table($table)
                ->select(\DB::raw('count(*) as cnt, date('.$field.') as date'))
                ->groupBy(\DB::raw('YEAR('.$field.'), MONTH('.$field.'), DAYOFMONTH('.$field.')'))
                ->orderBy($field, 'desc')
                ->limit($days)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                $data[$stat->date] = $stat->cnt;
            }

            $result = [];
            $start  = Carbon::now()->endOfDay();
            $end    = Carbon::now()->subDays($days-1);

            for ($date = $end->copy(); $date->lte($start); $date=$date->copy()->addDay()) {
                $day = (string)$date->toDateString();
                $result[$day] = isset($data[$day]) ? (int)$data[$day] : 0;
            }

            return $result;
        });
    }

    public static function weeklyTrends($table, $field = 'created_at', $weeks = 12)
    {
        return \Cache::remember('weekly_'.$table.'_'.$field.'_'.$weeks, 3600, function () use ($table, $field, $weeks) {
            $stats = \DB::table($table)
                ->select(\DB::raw('count(*) as cnt, '.$field.', WEEKOFYEAR('.$field.') as woy'))
                ->groupBy(\DB::raw('YEAR('.$field.'), WEEKOFYEAR('.$field.')'))
                ->orderBy($field, 'desc')
                ->limit($weeks+1)
                ->get();

            $data = [];

            foreach ($stats as $stat) {
                if (Carbon::parse($stat->{$field})->lt(Carbon::now()->subWeeks($weeks))) {
                    continue;
                }
                $data[$stat->woy] = $stat->cnt;
            }

            $result = [];
            $start  = Carbon::now()->endOfDay();
            $end    = Carbon::now()->subWeeks($weeks-1);

            for ($date = $end->copy(); $date->lte($start); $date=$date->copy()->addWeek()) {
                $week = (int)$date->format('W');
                $result[$week] = isset($data[$week]) ? (int)$data[$week] : 0;
            }

            return $result;
        });
    }

}