<?php

namespace PRStats\Models\Traits;

trait FormatScoreTrait
{
    public function formatValue($value)
    {
        if ($value < 10000) {
            return $value;
        }

        $readable = array("",  "k", "M", "B");
        $index=0;
        while($value > 1000){
            $value /= 1000;
            $index++;
        }
        return(round($value, 1).$readable [$index]);
    }

    public function formatScore($field)
    {
        $value = (int)$this->{$field};

        return $this->formatValue($value);
    }

    public function formatValueHtml($value)
    {
        if ($value < 10000) {
            return $value;
        }

        $readable = $this->formatValue($value);
        $value    = number_format($value, 0);

        return "<abbr title=\"$value\">$readable</abbr>";
    }


    public function formatScoreHtml($field)
    {
        $value = (int)$this->{$field};

        if ($value < 10000) {
            return $value;
        }

        $readable = $this->formatValue($value);
        $value    = number_format($value, 0);

        return "<abbr title=\"$value\">$readable</abbr>";
    }
}