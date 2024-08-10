@extends('report.layouts.app')
@section('title')
    تقرير تحويل الحليب الى المصانع
@endsection
@section('colspan')
    14
@endsection
@section('thead')
    <tr>
        <th>#</th>
        <th>اليوم</th>
        <th>التاريخ</th>
        <th>الوقت</th>
        <th>الفترة</th>
        <th>اسم الجمعية</th>
        <th>اسم المصنع</th>
        <th>اسم ألسائق</th>
        <th>وسيلة النقل</th>
        <th>الكمية</th>
        <th>الحالة</th>
        <th colspan="3">ملاحظات</th>
    </tr>
@endsection
@section('tbody')
    @foreach ($data as $item)
        <tr>
            <td>{{ $item['id'] }}</td>
            <td>{{ $item['day'] }}</td>
            <td>{{ $item['date'] }}</td>
            <td>{{ $item['time'] }}</td>
            <td>{{ $item['period'] }}</td>
            <td>{{ $item['association_name'] }}</td>
            <td>{{ $item['factory_name'] }}</td>
            <td>{{ $item['driver_name'] }}</td>
            <td>{{ $item['means_of_transportation'] }}</td>
            <td>{{ $item['quantity'] }}</td>
            @if ($item['status'])
                <td>تم الاستلام</td>
            @else
                <td>لم يتم الاستلام</td>
            @endif
            <td colspan="3">{{ $item['notes'] }}</td>
        </tr>
    @endforeach
@endsection
