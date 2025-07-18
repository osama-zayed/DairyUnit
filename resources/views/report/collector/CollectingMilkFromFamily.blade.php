@extends('report.layouts.app')
@section('title')
    تقرير تجميع الحليب من الاسر فرع الجمعية {{ auth()->user()->name }}
@endsection
@section('colspan')
    8
@endsection
@section('thead')
    <tr>
        <th>#</th>
        <th>أسم الاسرة</th>
        {{-- <th>فرع الجمعية</th>
        <th>أسم الجمعية</th> --}}
        <th>اليوم</th>
        <th>التاريخ</th>
        <th>الوقت</th>
        <th>الفترة</th>
        <th>الكمية</th>
        <th>ملاحظات</th>
    </tr>
@endsection
@section('tbody')
    @foreach ($data as $item)
        <tr>
            <td>{{ $item['id'] }}</td>
            <td>{{ $item['family_name'] }}</td>
            {{-- <td>{{ $item['association_branch_name'] }}</td>
            <td>{{ $item['association_name'] }}</td> --}}
            <td>{{ $item['day'] }}</td>
            <td>{{ $item['date'] }}</td>
            <td>{{ $item['time'] }}</td>
            <td>{{ $item['period'] }}</td>
            <td>{{ $item['quantity'] }}</td>
            <td>{{ $item['nots'] }}</td>
        </tr>
    @endforeach
    <tr >
        <td colspan="4" style="text-align: center">الاجمالي</td>
        <td colspan="4"  style="text-align: center">{{ $quantity }} لتر</td>
    </tr>
@endsection
