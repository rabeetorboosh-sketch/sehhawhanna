<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض إسناد المهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">تفاصيل الإسناد</h3>
                <div class="info-grid">

                    <div class="info-card">
                        <span class="info-title">المهمة</span>
                        <span class="info-content">
                            {{ ($assignment->task?->user_control_unit)
                                ? $assignment->task?->user_control_unit."-"
                                : $assignment->task?->controlUnit?->name."-" }}
                            {{ $assignment->task?->item->name ?? '-' }}
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">نوع المهمة</span>
                        <span class="info-content">
                            @if($assignment->task_type === 'App\Models\DailyControlItem')
                                رقابة يومية
                            @elseif($assignment->task_type === 'App\Models\ReportItem')
                                بلاغ
                            @elseif($assignment->task_type === 'App\Models\Task')
                                مهمة
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المسنِد</span>
                        <span class="info-content">{{ $assignment->user?->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الموظف</span>
                        <span class="info-content">{{ $assignment->employee?->item->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">وقت الإسناد</span>
                        <span class="info-content">{{ $assignment->assigned_at ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">آخر موعد للتسليم</span>
                        <span class="info-content">{{ $assignment->due_date ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">نوع التكرار</span>
                        <span class="info-content">
                            @if($assignment->recurrence_type === 'daily') يومي
                            @elseif($assignment->recurrence_type === 'weekly') أسبوعي
                            @elseif($assignment->recurrence_type === 'monthly') شهري
                            @else بدون تكرار @endif
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @switch($assignment->status)
                                @case('new') <span class="status new">جديدة</span> @break
                                @case('in_progress') <span class="status pending">جارية</span> @break
                                @case('completed') <span class="status completed">منجزة</span> @break
                                @case('cancelled') <span class="status cancelled">ملغاة</span> @break
                                @default -
                            @endswitch
                        </span>
                    </div>
                </div>

                {{-- الأيام المتكررة --}}
                @if($assignment->recurrence_type === 'weekly')
                    <h3 class="section-title">الأيام الأسبوعية</h3>
                    <div class="info-grid">
                        <div class="info-card full">
                            <span class="info-content">
                                @foreach($assignment->days as $day)
                                    @switch($day->day_of_week)
                                        @case(1) الأحد @break
                                        @case(2) الإثنين @break
                                        @case(3) الثلاثاء @break
                                        @case(4) الأربعاء @break
                                        @case(5) الخميس @break
                                        @case(6) الجمعة @break
                                        @case(0) السبت @break
                                        @default غير محدد
                                    @endswitch{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </span>
                        </div>
                    </div>
                @elseif($assignment->recurrence_type === 'monthly')
                    <h3 class="section-title">الأيام الشهرية</h3>
                    <div class="info-grid">
                        <div class="info-card full">
                            <span class="info-content">
                                @foreach($assignment->days as $day)
                                    {{ $day->day_of_month }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </span>
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex gap-3">
                    @if(!(parse_url(url()->previous(), PHP_URL_PATH) === '/myTasks'))
                        <a href="{{ route('task_assignments.edit', $assignment->id) }}" class="btn btn-worn">تعديل</a>
                    @endif
                    <a href="{{ route('task_assignments.receipt', $assignment->id) }}" class="btn btn-primary">استلام</a>
                    <a href="{{ url()->previous()}}" class="btn btn-secondary">رجوع</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
