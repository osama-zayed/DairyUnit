<?php

namespace App\Traits;

use DateTime;

trait FormatData
{
    public static function formatCollectingMilkFromFamilyData($CollectingMilkFromFamily)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $CollectingMilkFromFamily->collection_date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));

        return [
            'id' => $CollectingMilkFromFamily->id,
            'collection_date_and_time' => $CollectingMilkFromFamily->collection_date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $CollectingMilkFromFamily->quantity,
            'family_id' => $CollectingMilkFromFamily->family_id,
            'family_name' => $CollectingMilkFromFamily->Family->name,
            'association_name' => $CollectingMilkFromFamily->association->name,
            'association_branch_name' => $CollectingMilkFromFamily->user->name,
            'nots' => $CollectingMilkFromFamily->nots,
        ];
    }
    
    
}
