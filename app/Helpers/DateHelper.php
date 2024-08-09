<?php

namespace App\Helpers;

class DateHelper
{
    public static function dayNameInIndonesian($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        // Pastikan $date adalah objek Carbon
        $dayName = $days[$date->format('l')];
        return $dayName;
    }
}
