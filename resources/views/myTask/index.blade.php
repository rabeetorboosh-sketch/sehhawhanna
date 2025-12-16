<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              مهامي اليومية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="table-title">المهام المباشرة</div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>المسنِد</th>
                        <th>وقت الإسناد</th>
                        <th>آخر موعد</th>
                        <th>نوع التكرار</th>
                        <th> نوع المهمة </th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($directAssignments as $assignment)

                        <tr>
                            <td>{{ $assignment->id }}</td>
                            <td>{{($assignment->task?->user_control_unit)?$assignment->task?->user_control_unit."-": $assignment->task?->controlUnit?->name ."-"}}  {{ $assignment->task?->item?->name  }}  </td>
                            <td>{{ $assignment->user?->name }}</td>
                            <td>{{ $assignment->assigned_at }}</td>
                            <td>{{ $assignment->due_date ?? '-' }}</td>
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
                            <td>

                                @if($assignment->task_type === 'App\Models\DailyControlItem')
                                    رقابة يومية
                                @elseif($assignment->task_type === 'App\Models\ReportItem')
                                    بلاغ
                                @elseif($assignment->task_type === 'App\Models\Task')
                                    مهمة
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @switch($assignment->status)
                                    @case('new') جديدة @break
                                    @case('in_progress') جارية @break
                                    @case('completed') منجزة @break
                                    @case('cancelled') ملغاة @break
                                    @default -
                                @endswitch
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('task_assignments.show', $assignment->id) }}" class="btn btn-secondary">عرض</a>
                                    <a href="{{ route('task_assignments.receipt', $assignment->id) }}" class="btn btn-worn">استلام</a>
                                    <form action="{{route('task_receipts.endreceipt')}}" method="post" style="display: inline-block">
                                        @csrf
                                        <input type="hidden" name="task_assignment_id" value="{{$assignment->id}}">
                                        <button class="btn btn-primary">انهاء</button>
                                    </form>



                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <br>
        <div class="table-title">المهام الدورية</div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>المسنِد</th>
                        <th>وقت الإسناد</th>
                        <th>آخر موعد</th>
                        <th>نوع التكرار</th>
                        <th> نوع المهمة </th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($periodicAssignments as $assignment)

                        <tr>
                            <td>{{ $assignment->id }}</td>
                            <td>{{($assignment->task?->user_control_unit)?$assignment->task?->user_control_unit."-": $assignment->task?->controlUnit?->name ."-"}}  {{ $assignment->task?->item?->name  }}  </td>
                            <td>{{ $assignment->user?->name }}</td>
                            <td>{{ $assignment->assigned_at }}</td>
                            <td>{{ !($assignment->occurrences?->isEmpty())? $assignment->occurrences->last()->getDate(): $assignment->due_date ?? '-' }}</td>
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
                            <td>

                                @if($assignment->task_type === 'App\Models\DailyControlItem')
                                    رقابة يومية
                                @elseif($assignment->task_type === 'App\Models\ReportItem')
                                    بلاغ
                                @elseif($assignment->task_type === 'App\Models\Task')
                                    مهمة
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @switch($assignment->status)
                                    @case('new') جديدة @break
                                    @case('in_progress') جارية @break
                                    @case('completed') منجزة @break
                                    @case('cancelled') ملغاة @break
                                    @default -
                                @endswitch
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('task_assignments.show', $assignment->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('task_assignments.receipt', [
    $assignment->id,
    !($assignment->occurrences?->isEmpty()) ? $assignment->occurrences->last()->id : null
]) }}" class="btn btn-secondary">استلام</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
