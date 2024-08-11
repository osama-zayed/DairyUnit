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

    public static function formatReceiptFromAssociationData($receiptFromAssociation)
    {
        $startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $receiptFromAssociation->start_time_of_collection);
        $startFormattedDate = $startDateTime->format('d/m/Y');
        $startFormattedTime = $startDateTime->format('h:i A');
        $startDayPeriod = self::getDayPeriodArabic($startDateTime->format('A'));
        $startDayOfWeek = self::getDayOfWeekArabic($startDateTime->format('l'));

        $endDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $receiptFromAssociation->end_time_of_collection);
        $endFormattedDate = $endDateTime->format('d/m/Y');
        $endFormattedTime = $endDateTime->format('h:i A');
        $endDayPeriod = self::getDayPeriodArabic($endDateTime->format('A'));
        $endDayOfWeek = self::getDayOfWeekArabic($endDateTime->format('l'));

        return [
            'id' => $receiptFromAssociation->id,
            'start_time_of_collection' => $receiptFromAssociation->start_time_of_collection,
            'end_time_of_collection' => $receiptFromAssociation->end_time_of_collection,
            'start_date' => $startFormattedDate,
            'end_date' => $endFormattedDate,
            'start_time' => $startFormattedTime,
            'end_time' => $endFormattedTime,
            'start_period' => $startDayPeriod,
            'end_period' => $endDayPeriod,
            'start_day' => $startDayOfWeek,
            'end_day' => $endDayOfWeek,
            'transfer_to_factory_id' => $receiptFromAssociation->transfer_to_factory_id,
            'transfer_quantity' => $receiptFromAssociation->transferToFactory->quantity,
            'receipt_quantity' => $receiptFromAssociation->quantity,
            'association_id' => $receiptFromAssociation->association->id,
            'association_name' => $receiptFromAssociation->association->name,
            'driver_id' => $receiptFromAssociation->driver_id,
            'driver_name' => $receiptFromAssociation->driver->name,
            'factory_id' => $receiptFromAssociation->factory_id,
            'factory_name' => $receiptFromAssociation->factory->name,
            'package_cleanliness' => trans("filament.resources.receiptFromAssociation.$receiptFromAssociation->package_cleanliness"),
            'transport_cleanliness' => trans("filament.resources.receiptFromAssociation.$receiptFromAssociation->transport_cleanliness"),
            'driver_personal_hygiene' => trans("filament.resources.receiptFromAssociation.$receiptFromAssociation->driver_personal_hygiene"),
            'ac_operation' => trans("filament.resources.receiptFromAssociation.$receiptFromAssociation->ac_operation"),
            'notes' => $receiptFromAssociation->notes,
        ];
    }

    public static function formatReturnTheQuantityData($ReturnTheQuantity)
    {
        $DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ReturnTheQuantity->created_at);
        $FormattedDate = $DateTime->format('d/m/Y');
        $FormattedTime = $DateTime->format('h:i A');
        $DayPeriod = self::getDayPeriodArabic($DateTime->format('A'));
        $DayOfWeek = self::getDayOfWeekArabic($DateTime->format('l'));

        return [
            'id' => $ReturnTheQuantity->id,
            'date' => $FormattedDate,
            'time' => $FormattedTime,
            'period' => $DayPeriod,
            'day' => $DayOfWeek,
            'quantity' =>  $ReturnTheQuantity->quantity,
            'return' => ($ReturnTheQuantity->return_to == "association") ? 'مردود الى جمعية ' . $ReturnTheQuantity->association->name :
                'مردود الى المؤسسة',
            'return_to' => $ReturnTheQuantity->return_to,
            'association_id' => $ReturnTheQuantity->association_id,
            'defective_quantity_due_to_coagulation' => $ReturnTheQuantity->defective_quantity_due_to_coagulation,
            'defective_quantity_due_to_impurities' => $ReturnTheQuantity->defective_quantity_due_to_impurities,
            'defective_quantity_due_to_density' => $ReturnTheQuantity->defective_quantity_due_to_density,
            'defective_quantity_due_to_acidity' => $ReturnTheQuantity->defective_quantity_due_to_acidity,
            'factory_name' => $ReturnTheQuantity->factory->name,
            'user_name' => $ReturnTheQuantity->user->name,

            'notes' => $ReturnTheQuantity->notes,
        ];
    }
}
