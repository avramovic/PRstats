<?php

namespace PRStats\Models\Traits;

trait HasCountryFlag
{
    public function getCountryFlagUrl($size = 24, $style = 'shiny')
    {
        return 'https://flagcdn.com/24x18/'.strtolower($this->country).'.png';
    }

    public function getCountryFlagHtml($size = 24, $style = 'shiny')
    {
        return $this->country ? '<img class="cf cf-'.$style.' cf-'.$size.'" src="'.$this->getCountryFlagUrl($size, $style).'" alt="'.$this->country.'" title="'.$this->country.'" />' : '';
    }
}