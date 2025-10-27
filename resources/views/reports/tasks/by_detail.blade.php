@extends('reports.tasks.filters')
@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">
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
@endsection
