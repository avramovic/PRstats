<?php

namespace PRStats\Models\Traits;

trait HasCountryFlag
{
    public function getCountryFlagUrl($size = 24, $style = 'shiny')
    {
        return 'https://www.countryflags.io/'.strtolower($this->country).'/'.$style.'/'.$size.'.png';
    }

    public function getCountryFlagHtml($size = 24, $style = 'shiny')
    {
        return $this->country ? '<img src="'.$this->getCountryFlagUrl($size, $style).'" alt="'.$this->country.'" title="'.$this->country.'" />' : '';
    }
}