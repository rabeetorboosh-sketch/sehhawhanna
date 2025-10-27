<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            margin: 20px;
            font-size: 14px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header img {
            height: 80px;
            display: block;
            margin: 0 auto 5px auto;
        }
        h1, h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }
        .note {
            text-align: left;
        }
        .summary {
            margin: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            background: #fcfdff;
            padding: 5px;
            box-shadow: inset 0 0 23px -13px #d8d8d8;
        }
        .num {
            border: 1px solid #e8e4e4;
            padding: 5px;
            strong {
                border: 1px dashed;
                margin: 0 5px 5px -3px;
                padding: 5px;
            }
        }
    </style>
</head>
<body>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        // عند تحميل الصفحة مباشرة نشغل الطباعة
        window.print();
    });

    // بعد انتهاء الطباعة أو الإلغاء، نرجع للصفحة السابقة
    window.onafterprint = function() {
        // نرجع للصفحة السابقة في التاريخ
        window.history.back();
    };

    // منع المستخدم من استخدام Ctrl+P يدوياً لإعادة التشغيل غير المقصود
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault(); // يمنع الاختصار الافتراضي
            window.print();
        }
    });
</script>
<header>
{{--    <img src="{{ asset('images/company_logo.png') }}" alt="Logo">--}}
    <h1> تقرير المهام </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>
@php

    $transToManagement=0;
    $Overdue=0;
@endphp
<div class="table-wrap">
    <div class="table-scroll">
        <table class="table sortable">
            <thead>
            <tr>
                <th>#</th>
                <th>الموظف </th>
                <th>المهمة  </th>
                <th>المسنِد  </th>
                <th>التاريخ  </th>
                <th>التكرار  </th>
                <th>تم حلها </th>
                <th>تم تحويلها للادارة </th>
                <th>تم تجاوز وقتها </th>
                <th>التقييم</th>
                <th>    تحليلي </th>


            </tr>
            </thead>
            <tbody>


            @foreach($assignments as $assignment)
                @php($overdue = $assignment->overdueDiff())


                @if(!$assignment->occurrences->isEmpty())

                    @foreach($assignment->occurrences as $occurrences)
                        @php($OccurrenceOverdue = $occurrences?->overdueDiff())

                        <tr         @if($OccurrenceOverdue && $OccurrenceOverdue['hours'] < 5 && $OccurrenceOverdue['hours'] >= 0 && $occurrences->receipt->isEmpty())
                                        class="worn"
                                    @elseif($OccurrenceOverdue && $OccurrenceOverdue['hours'] < 0 && $occurrences->receipt->isEmpty())
                                        class="danger"
                                    @elseif(!$occurrences?->receipt->isEmpty())
                                        class="done"
                            @endif>
                            <td>{{ $assignment->id }}</td>
                            <td>{{ $assignment->employee?->item?->name }}</td>
                            <td>{{($assignment->task?->user_control_unit)?$assignment->task?->user_control_unit."-": $assignment->task?->controlUnit?->name ."-"}}  {{ $assignment->task?->item?->name  }}  </td>
                            <td>{{ $assignment->user?->name }}</td>
                            <td>{{ $occurrences->date }}</td>
                            <td>
                                @if($assignment->recurrence_type === 'daily')
                                    يومي
                                @elseif($assignment->recurrence_type === 'weekly')
                                    أسبوعي
                                @elseif($assignment->recurrence_type === 'monthly')
                                    شهري
                                @else
                                    بدون تكرار
                                @endif
                            </td>
                            <td>{{  $occurrences?->receipt->isEmpty()?' لا  ':'نعم' }}</td>
                            <td>
                                @if($assignment->receipt?->first()?->forwarded_to_management==1)
                                    نعم
                                    @php(++$transToManagement)
                                @else
                                    لا
                                @endif
                            </td>
                            <td>
                                @if($occurrences->isOverdue() )
                                    ب    <strong>{{ $OccurrenceOverdue ? -$OccurrenceOverdue['hours'] . ':' . $OccurrenceOverdue['minutes'] : '' }}</strong>   ساعات

                                    @php(++$Overdue)
                                @else
                                    متبقي :{{ $OccurrenceOverdue ? $OccurrenceOverdue['hours'] . ':' . $OccurrenceOverdue['minutes'] : 'غير محدد' }} ساعات
                                @endif
                            </td>
                            <td>{{$occurrences->receipt?->first()?->completion_percentage?? '--'.'%'}}  </td>
                            <td>

                                @if( $occurrences->receipt && !$occurrences->receipt->isEmpty())
                                    <a href="{{route('task_receipts.show',$occurrences->receipt?->first()->id)}}" class="btn btn-worn">عرض</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                @else
                    <tr         @if($overdue && $overdue['hours'] < 5 && $overdue['hours'] >= 0 && $assignment->receipt->isEmpty())
                                    class="worn"
                                @elseif($overdue && $overdue['hours'] < 0 && $assignment->receipt->isEmpty())
                                    class="danger"
                                @elseif(!$assignment->receipt->isEmpty())
                                    class="done"
                        @endif>
                        <td>{{ $assignment->id }}</td>
                        <td>{{ $assignment->employee?->item?->name }}</td>
                        <td>{{($assignment->task?->user_control_unit)?$assignment->task?->user_control_unit."-": $assignment->task?->controlUnit?->name ."-"}}  {{ $assignment->task?->item?->name  }}  </td>
                        <td>{{ $assignment->user?->name }}</td>
                        <td>{{ $assignment->created_at }}</td>
                        <td>
                            @if($assignment->recurrence_type === 'daily')
                                يومي
                            @elseif($assignment->recurrence_type === 'weekly')
                                أسبوعي
                            @elseif($assignment->recurrence_type === 'monthly')
                                شهري
                            @else
                                بدون تكرار
                            @endif
                        </td>
                        <td>{{  $assignment->receipt->isEmpty()?' لا  ':'نعم' }}</td>
                        <td>
                            @if($assignment->receipt?->first()?->forwarded_to_management==1)
                                نعم
                                @php(++$transToManagement)
                            @else
                                لا
                            @endif
                        </td>
                        <td>
                            @if($assignment->isOverdue() )
                                ب    <strong>{{ $overdue ? -$overdue['hours'] . ':' . $overdue['minutes'] : '' }}</strong>   ساعات

                                @php(++$Overdue)
                            @else
                                متبقي :{{ $overdue ? $overdue['hours'] . ':' . $overdue['minutes'] : 'غير محدد' }} ساعات

                            @endif
                        </td>
                        <td>{{$assignment->receipt?->first()?->completion_percentage?? '--'.'%'}}  </td>

                        <td>
                            @if(!$assignment->receipt->isEmpty())
                                <a href="{{route('task_receipts.show',$assignment->receipt?->first()->id)}}" class="btn btn-worn">عرض</a>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
</div>

<div class="summary">
    <div class="num">
        عدد المهام:<strong>{{$assignments->count()}}</strong>
    </div>
    <div class="num">
        عدد المستلمة:<strong>{{ $assignments->filter(fn($a) => !$a->receipt->isEmpty())->count() }}</strong>
    </div>
    <div class="num">
        عدد المحولة للادارة:<strong>{{$transToManagement}}</strong>
    </div>
    <div class="num">
        عدد التي تم تجاوز وقتها :<strong>{{$Overdue}}</strong>
    </div>
</div>

</body>
</html>
