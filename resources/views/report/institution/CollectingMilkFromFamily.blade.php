@extends('report.layouts.app')
@section('title')
تقرير تجميع الحليب من الاسر
@endsection
@section('colspan')22
@endsection
@section('thead')
    <tr>
        <th>م</th>
        <th>اسم السلسلة</th>
        <th>المجال</th>
        <th>الحلقة</th>
        <th>الاهداف</th>
        <th>المشروع</th>
        <th>الانشطة</th>
        <th>القيمة المستهدفة</th>
        <th>مؤاشر القيمة المستهدفة</th>
        <th>وزن النشاط</th>
        <th>الاجراءات</th>
        <th>وزن الإجراء</th>
        <th>مدة الإجراء (يوم)</th>
        <th>بداية تنفيذ الإجراء</th>
        <th>نهاية تنفيذ الإجراء</th>
        <th>التكلفة</th>
        <th>مصدر التمويل</th>
        <th>الجهة المشرفة</th>
        <th>الجهة المنفذة</th>
        <th>الحالة</th>
        <th>وسائل التحقق</th>
        <th>المعني بتنفيذ النشاط/الاجراء</th>

    </tr>
@endsection
@section('tbody')
    {{-- @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->chain->name }}</td>
                    <td>{{ $item->domain->name }}</td>
                    <td>{{ $item->ring->name }}</td>
                    <td>{{ $item->chain->Goals }}</td>
                    <td>{{ $item->project->name }}</td>
                    <td>{{ $item->activity->name }}</td>
                    <td>{{ $item->activity->target_value }}</td>
                    <td>{{ $item->activity->target_indicator }}</td>
                    <td>{{ $item->activity->activity_weight }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->procedure_weight }}</td>
                    <td>{{ $item->procedure_duration_days }}</td>
                    <td>{{ $item->procedure_start_date }}</td>
                    <td>{{ $item->procedure_end_date }}</td>
                    <td>{{ $item->cost }}</td>
                    <td>{{ $item->funding_source }}</td>
                    <td>{{ $item->supervisory_authority }}</td>
                    <td>{{ $item->executing_agency }}</td>
                    @if ($item->status)
                    <td>اكتملت</td>
                    @else
                    <td>قيد العمل</td>
                    @endif
                    <td>{{ $item->verification_methods }}</td>
                    <td>{{ $item->user->name }}</td>

                </tr>
            @endforeach --}}
@endsection
