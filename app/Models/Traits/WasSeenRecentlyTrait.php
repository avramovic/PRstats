<?php

namespace PRStats\Models\Traits;

trait WasSeenRecentlyTrait
{
    public function wasSeenRecently($mins = 3)
    {
        return $this->updated_at->diffInMinutes() <= $mins;
    }

    public function inGameTime()
    {
        if (empty($this->pivot)) {
            return '-';
        }

        $diff = $this->pivot->created_at->diffForHumans($this->pivot->updated_at, \Carbon\CarbonInterface::DIFF_ABSOLUTE);

        return str_replace(['seconds', 'second', 'minutes', 'minute', 'hours', 'hour'], ['sec', 'sec', 'min', 'min', 'hr', 'hr'], $diff);
    }
}