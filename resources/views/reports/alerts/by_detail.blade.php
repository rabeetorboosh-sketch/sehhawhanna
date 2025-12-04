@extends('reports.alerts.filters')
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
                    <th>الوصف  </th>

                    <th>المتسبب </th>

                    <th>مسندة </th>
                    <th>تم حلها  </th>
                </tr>
                </thead>
                <tbody>

                @foreach($reports as $report)
                    @foreach($report->items as $RItem)

                        <tr>
                            <td>{{ $RItem->id }}</td>
                            <td>{{ $report->department?->name }}</td>
                            <td>{{ $RItem->item?->name ?? 'بدون تحديد' }}</td>
                            <td>{{ $RItem->controlUnit?->name?? $RItem->user_control_unit??'' }}</td>
                            <td>{{ $report->created_at }}</td>
                            <td>{{ $RItem->issue_description ?? ' ' }}</td>
                            <td>{{ $RItem->causer?->item?->name ?? 'غير محدد' }}</td>


                            @if(isset($RItem->assignments) && $RItem->assignments->isNotEmpty())
                                @php($assiments++)
                                <td>نعم</td>
                                @foreach($RItem->assignments as $assignment)
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
