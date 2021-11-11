<?php

namespace App\Helper;

class ClearScreenHelper
{
    public static function clearScreen()
    {
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
    }
}