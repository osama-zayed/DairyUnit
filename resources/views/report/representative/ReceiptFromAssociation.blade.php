@extends('report.layouts.app')
@section('title')
    تقرير استلام الحليب من الجمعيات
@endsection
@section('colspan')
    19
@endsection
@section('thead')
    <tr>
        <th>#</th>
        <th>أسم الجمعية</th>
        <th>أسم المصنع</th>
        <th>أسم السائق</th>
        <th>اليوم بداية الاستلام</th>
        <th>التاريخ بداية الاستلام</th>
        <th>الوقت بداية الاستلام</th>
        <th>الفترة بداية الاستلام</th>
        <th>اليوم نهاية الاستلام</th>
        <th>التاريخ نهاية الاستلام</th>
        <th>الوقت نهاية الاستلام</th>
        <th>الفترة نهاية الاستلام</th>
        <th>الكمية المحولة</th>
        <th>الكمية المستلمة</th>
        <th>نظافة العبوة</th>
        <th>نظافة النقل</th>
        <th>النظافة الشخصية للسائق</th>
        <th>تشغيل التكييف</th>
        <th>ملاحظات</th>
    </tr>
@endsection
@section('tbody')
    @foreach ($data as $item)
        <tr>
            <td>{{ $item['id'] }}</td>
            <td>{{ $item['association_name'] }}</td>
            <td>{{ $item['factory_name'] }}</td>
            <td>{{ $item['driver_name'] }}</td>
            <td>{{ $item['start_day'] }}</td>
            <td>{{ $item['start_date'] }}</td>
            <td>{{ $item['start_time'] }}</td>
            <td>{{ $item['start_period'] }}</td>
            <td>{{ $item['end_day'] }}</td>
            <td>{{ $item['end_date'] }}</td>
            <td>{{ $item['end_time'] }}</td>
            <td>{{ $item['end_period'] }}</td>
            <td>{{ $item['receipt_quantity'] }}</td>
            <td>{{ $item['transfer_quantity'] }}</td>
            <td>{{ $item['package_cleanliness'] }}</td>
            <td>{{ $item['transport_cleanliness'] }}</td>
            <td>{{ $item['driver_personal_hygiene'] }}</td>
            <td>{{ $item['ac_operation'] }}</td>
            <td>{{ $item['notes'] }}</td>
        </tr>
    @endforeach
    <tr >
        <td colspan="9" style="text-align: center">الاجمالي</td>
        <td colspan="10"  style="text-align: center">{{ $quantity }} لتر</td>
    </tr>
@endsection
