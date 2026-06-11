<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Utilities extends Model
{
    protected function weekDaysSm()
    {
        $dias = [];

        for ($i = 0; $i < 7; $i++) {
            $date = new DateTime();
            $date->modify("Sunday +$i day");
            $dias[] = $date->format('D');
        }

        return $dias;
    }
}
