<?php

namespace PRStats\Models\Traits;

trait WasSeenRecentlyTrait
{
    public function wasSeenRecently($mins = 3)
    {
        return $this->updated_at->diffInMinutes() <= $mins;
    }
}