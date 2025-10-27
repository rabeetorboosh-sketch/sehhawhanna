<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إسناد المهام
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('task_assignments.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>المسنِد</th>
                        <th>الموظف</th>
                        <th>وقت الإسناد</th>
                        <th>آخر موعد</th>
                        <th>نوع التكرار</th>
                        <th> نوع المهمة </th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assignments as $assignment)

                        <tr>
                            <td>{{ $assignment->id }}</td>
                            <td>{{($assignment->task?->user_control_unit)?$assignment->task?->user_control_unit."-": $assignment->task?->controlUnit?->name ."-"}}  {{ $assignment->task?->item?->name  }}  </td>
                            <td>{{ $assignment->user?->name }}</td>
                            <td>{{ $assignment->employee?->item->name }}</td>
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
                                    @if(Auth::user()->permissions('5-operations-assignments')?->can_update == 1)
                                    <a href="{{ route('task_assignments.edit', $assignment->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                    @if(Auth::user()->permissions('5-operations-assignments')?->can_delete == 1)
                                    <form id="delete-form-{{ $assignment->id }}" action="{{ route('task_assignments.destroy', $assignment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $assignment->id }})">
                                            حذف
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('task_assignments.show', $assignment->id) }}" class="btn btn-primary">عرض</a>
                                    @if(Auth::user()->permissions('5-operations-receipts')?->can_create == 1 and $assignment->receipt->isEmpty()  and $assignment->employee_id ==Auth::User()->employee->id)
                                    <a href="{{ route('task_assignments.receipt', $assignment->id) }}" class="btn btn-secondary">استلام</a>
                                    @elseif(!$assignment->receipt->isEmpty() and (Auth::user()->permissions('5-operations-receipts')?->can_show == 1))
                                        <a href="{{ route('task_receipts.show', $assignment->receipt?->first()->id) }}" class="btn btn-secondary">عرض الاستلام</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- روابط التصفح -->
            <div class="mt-4">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
