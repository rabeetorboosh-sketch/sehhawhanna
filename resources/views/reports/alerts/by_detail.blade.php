@extends('reports.monitorings.filters')
@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">
    @php
        $proplemsCount = 0;
        $assiments = 0;
        $receipt = 0;
    @endphp
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>القسم </th>
                    <th> البند </th>
                    <th>وحدة الرقابة </th>
                    <th>التاريخ  </th>
                    <th>المشكلة </th>
                    <th>المتسبب </th>
                    <th>الحالة </th>
                    <th>مسندة </th>
                    <th>تم حلها  </th>
                </tr>
                </thead>
                <tbody>

                @foreach($dailyControls as $dailyControl)
                    @foreach($dailyControl->items as $CItem)
                        <tr>
                            <td>{{ $CItem->id }}</td>
                            <td>{{ $CItem->controlUnit?->section?->name }}</td>
                            <td>{{ $CItem->item?->name ?? 'بدون تحديد' }}</td>
                            <td>{{ $CItem->controlUnit?->name }}</td>
                            <td>{{ $dailyControl->created_at }}</td>
                            <td>{{ $CItem->description ?? 'ليس فيها مشكلة' }}</td>
                            <td>{{ $CItem->causer?->item?->name }}</td>
                            <td>
                                @if($CItem->is_correct == 1)
                                    ليس فيها مشكلة
                                @else
                                    @php(++$proplemsCount)
                                    فيها مشكلة
                                @endif
                            </td>
                            @if(isset($CItem->assignments) && $CItem->assignments->isNotEmpty())
                                @php($assiments++)
                                <td>نعم</td>
                                @foreach($CItem->assignments as $assignment)
                                    @if(isset($assignment->receipt) && $assignment->receipt->isNotEmpty())
                                        @php($receipt++)
                                        <td>نعم</td>
                                        @break
                                    @else
                                        <td>لا</td>
                                        @break
                                    @endif
                                @endforeach
                            @else
                                <td>لا</td>
                                <td>لا</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="summary">
        <div class="num">
            عدد المشاكل: <strong>{{ $proplemsCount }}</strong>
        </div>
        <div class="num">
            عدد الموجهة: <strong>{{ $assiments }}</strong>
        </div>
        <div class="num">
            عدد المحلولة: <strong>{{ $receipt }}</strong>
        </div>
    </div>
@endsection
