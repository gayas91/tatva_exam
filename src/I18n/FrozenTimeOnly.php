<?php

namespace App\I18n;

use Cake\I18n\FrozenTime;

class FrozenTimeOnly extends FrozenTime
{
    protected static $_toStringFormat = [
        \IntlDateFormatter::NONE,
        \IntlDateFormatter::SHORT
    ];
}