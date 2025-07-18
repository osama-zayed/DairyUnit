@extends('report.layouts.app')
@section('title')
   تقرير مردود الحليب للمؤاسسة
@endsection
@section('colspan')
    11
@endsection
@section('thead')
    <tr>
        <th>#</th>
        <th>اليوم</th>
        <th>التاريخ</th>
        <th>الوقت</th>
        <th>الفترة</th>
        <th>الكمية التالفة بسبب التخثر</th>
        <th>الكمية التالفة بسبب الشوائب</th>
        <th>الكمية التالفة بسبب الكثافة</th>
        <th>الكمية التالفة بسبب الحموضة</th>
        <th>اجمالي التالف</th>
        <th>ملاحظات</th>
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
            <td>{{ $item['defective_quantity_due_to_coagulation'] }}</td>
            <td>{{ $item['defective_quantity_due_to_impurities'] }}</td>
            <td>{{ $item['defective_quantity_due_to_density'] }}</td>
            <td>{{ $item['defective_quantity_due_to_acidity'] }}</td>
            <td>{{ $item['quantity'] }}</td>
            <td>{{ $item['notes'] }}</td>
        </tr>
    @endforeach
    <tr >
        <td colspan="5" style="text-align: center">الاجمالي</td>
        <td colspan="6"  style="text-align: center">{{ $quantity }} لتر</td>
    </tr>
@endsection
