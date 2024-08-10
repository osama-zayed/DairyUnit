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
    public static function formatReceiptInvoiceFromStoreData($ReceiptInvoiceFromStore)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ReceiptInvoiceFromStore->date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));
        return [
            'id' => $ReceiptInvoiceFromStore->id,
            'date_and_time' => $ReceiptInvoiceFromStore->date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $ReceiptInvoiceFromStore->quantity,
            'association_id' => $ReceiptInvoiceFromStore->association->id,
            'association_name' => $ReceiptInvoiceFromStore->association->name,
            'association_branch_id' => $ReceiptInvoiceFromStore->associationsBranche->id,
            'association_branch_name' => $ReceiptInvoiceFromStore->associationsBranche->name,
            'notes' => $ReceiptInvoiceFromStore->notes,
        ];
    }
    public static function formatTransferToFactoryData($TransferToFactory)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $TransferToFactory->date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));
        return [
            'id' => $TransferToFactory->id,
            'date_and_time' => $TransferToFactory->date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $TransferToFactory->quantity,
            'association_id' => $TransferToFactory->association->id,
            'association_name' => $TransferToFactory->association->name,
            'driver_id' => $TransferToFactory->driver_id,
            'driver_name' => $TransferToFactory->driver->name,
            'factory_id' => $TransferToFactory->factory_id,
            'factory_name' => $TransferToFactory->factory->name,
            'means_of_transportation' => $TransferToFactory->means_of_transportation,
            'notes' => $TransferToFactory->notes,
            'status' => $TransferToFactory->status,


        ];
    }
}
