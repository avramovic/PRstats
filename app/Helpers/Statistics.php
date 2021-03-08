<?php namespace PRStats\Helpers;

use Carbon\Carbon;

class Statistics
{

    public static function dailyNew($table, $days = 7)
    {
        $stats = \DB::table($table)
            ->select(\DB::raw('count(*) as cnt, date(created_at) as date'))
            ->groupBy(\DB::raw('YEAR(created_at), MONTH(created_at), DAYOFMONTH(created_at)'))
            ->orderBy('created_at', 'desc')
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
    }

    public static function weeklyNew($table, $weeks = 12)
    {
        $stats = \DB::table($table)
            ->select(\DB::raw('count(*) as cnt, WEEKOFYEAR(created_at) as woy'))
            ->groupBy(\DB::raw('YEAR(created_at), WEEKOFYEAR(created_at)'))
            ->orderBy('created_at', 'desc')
            ->limit($weeks)
            ->get();

        $data = [];

        foreach ($stats as $stat) {
            $data[$stat->woy] = $stat->cnt;
        }

        $result = [];
        $start  = Carbon::now()->endOfDay();
        $end    = Carbon::now()->subWeeks($weeks-1);

        for ($date = $end->copy(); $date->lte($start); $date=$date->copy()->addWeek()) {
            $week = (int)$date->format('W')-1;
            $result[$week] = isset($data[$week]) ? (int)$data[$week] : 0;
        }

        return $result;
    }

}