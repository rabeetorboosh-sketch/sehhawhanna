<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل إسناد مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('task_assignments.update', $assignment->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <!-- اختيار نوع الإسناد -->
                <div class="form-group">
                    <label>اسناد من:</label>
                    <select name="assign_type" id="assignType" required>
                        <option value="">اختر المصدر</option>
                        <option value="1" {{ $assignment->task_type == \App\Models\Task::class ? 'selected' : '' }}>مهام</option>
                        <option value="2" {{ $assignment->task_type == \App\Models\ReportItem::class ? 'selected' : '' }}>بلاغات</option>
                        <option value="3" {{ $assignment->task_type == \App\Models\DailyControlItem::class ? 'selected' : '' }}>رقابة يومية</option>
                    </select>
                </div>

                <!-- hidden task_type -->
                <input type="hidden" name="task_type" id="taskTypeInput" value="{{ $assignment->task_type }}">

                <div class="form-group assign-section" id="tasks-section" style="display:none;">
                    <label>المهمة</label>
                    <div class="search-filter">
                        <input type="text" id="task-name" placeholder="ابحث عن مهمة" class="name-searcher">
                        @foreach($tasks as $task)
                            <div class="item-card">
                                <label>
                                    <input type="radio" class="itemsCheck checkbox"
                                           name="task_id"
                                           value="{{ $task->id }}"
                                        {{ $assignment->task_type == \App\Models\Task::class && $assignment->task_id == $task->id ? 'checked' : '' }}>
                                    <span>{{ $task->item->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- البلاغات -->
                <div class="form-group assign-section" id="reports-section" style="display:none;">
                    <label>البلاغ</label>
                    <div class="search-filter">
                        <input type="text" id="report-name" placeholder="ابحث عن بلاغ" class="name-searcher">
                        <input id="report-date" type="date" class="name-searcher">

                        @foreach($reports as $report)
                            @foreach($report->items as $item)
                                <div class="item-card">
                                    <label>
                                        <input type="radio"
                                               data-date="{{ $item->created_at }}"
                                               class="itemsCheck checkbox"
                                               name="task_id"
                                               value="{{ $item->id }}"
                                            {{ $assignment->task_type == \App\Models\ReportItem::class && $assignment->task_id == $item->id ? 'checked' : '' }}>
                                        <span>{{ $item->controlUnit?->name ?? $item->user_control_unit }} -> {{ $item->item?->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- الرقابة اليومية -->
                <div class="form-group assign-section" id="monitorings-section" style="display:none;">
                    <label>عناصر الرقابة</label>
                    <div class="search-filter">
                        <input type="text" id="monitoring-name" placeholder="ابحث عن عنصر" class="name-searcher">
                        <input type="date" id="monitoring-date" class="name-searcher">

                        @foreach($monitorings as $monitoring)
                            @foreach($monitoring->items as $item)
                                <div class="item-card">
                                    <label>
                                        <input type="radio"
                                               data-date="{{ $item->created_at }}"
                                               class="itemsCheck checkbox"
                                               name="task_id"
                                               value="{{ $item->id }}"
                                            {{ $assignment->task_type == \App\Models\DailyControlItem::class && $assignment->task_id == $item->id ? 'checked' : '' }}>
                                        <span>{{ $item->controlUnit->name .' -> '. $item->item?->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- الموظف -->
                <div class="form-group">
                    <label>الموظف</label>
                    <select name="employee_id" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $assignment->employee_id == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- وقت الإسناد -->
            <div class="row-2">
                <div class="form-group">
                    <label>وقت الإسناد</label>
                    <input type="datetime-local"
                           name="assigned_at"
                           value="{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('Y-m-d\TH:i') }}"
                           required>
                </div>
                <div class="form-group">
                    <label>آخر موعد لإنجاز المهام</label>
                    <input type="datetime-local"
                           name="due_date"
                           value="{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <!-- الحالة -->
            <div class="row-2">
                <div class="form-group">
                    <label>حالة المهمة</label>
                    <select name="status" required>
                        <option value="new" {{ $assignment->status=='new'?'selected':'' }}>جديدة</option>
                        <option value="in_progress" {{ $assignment->status=='in_progress'?'selected':'' }}>جارية</option>
                        <option value="completed" {{ $assignment->status=='completed'?'selected':'' }}>منجزة</option>
                        <option value="cancelled" {{ $assignment->status=='cancelled'?'selected':'' }}>ملغاة</option>
                    </select>
                </div>

                <!-- التكرار -->
                <div class="form-group" id="recurrence-row">
                    <label>نوع التكرار</label>
                    <select name="recurrence_type" id="recurrenceType">
                        <option value="" {{ $assignment->recurrence_type==''?'selected':'' }}>بدون تكرار</option>
                        <option value="daily" {{ $assignment->recurrence_type=='daily'?'selected':'' }}>يومي</option>
                        <option value="weekly" {{ $assignment->recurrence_type=='weekly'?'selected':'' }}>أسبوعي</option>
                        <option value="monthly" {{ $assignment->recurrence_type=='monthly'?'selected':'' }}>شهري</option>
                    </select>
                </div>
            </div>

            <!-- اختيار الأيام -->
            <div class="form-group" id="days-wrapper" style="display:none; border:1px solid gray; border-radius:10px; padding:10px;">
                <h3>الأيام</h3>

                <!-- أسبوعي -->
                <div id="weekly-days" style="display:none;">
                    <label>اختر أيام الأسبوع</label>
                    <div class="row-3">
                        @php
                            $weekDays = ['1'=>'الأحد','2'=>'الإثنين','3'=>'الثلاثاء','4'=>'الأربعاء','5'=>'الخميس','6'=>'الجمعة','0'=>'السبت'];
                        @endphp
                        @foreach($weekDays as $num => $day)
                            <div>
                                <input type="checkbox" name="days[]" value="{{ $num }}" id="day{{ $num }}" class="checkbox"
                                    {{ in_array($num, $assignment->days->pluck('day_of_week')->toArray()) ? 'checked' : '' }}>
                                <label for="day{{ $num }}">{{ $day }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- شهري -->
                <div id="monthly-days" style="display:none;">
                    <label>اختر أيام الشهر</label>
                    <div class="row-3" style="max-height:200px; overflow-y:auto;">
                        @for($i=1; $i<=31; $i++)
                            <div>
                                <input type="checkbox" class="checkbox" name="days[]" value="{{ $i }}" id="mday{{ $i }}"
                                    {{ in_array($i, $assignment->days->pluck('day_of_month')->toArray()) ? 'checked' : '' }}>
                                <label for="mday{{ $i }}">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- الأزرار -->
            <div class="actions">
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>

    <script>
        const assignSelect = document.getElementById('assignType');
        const sections = {
            "1": document.getElementById('tasks-section'),
            "2": document.getElementById('reports-section'),
            "3": document.getElementById('monitorings-section')
        };

        // أظهر القسم الصحيح عند التعديل
        function toggleAssignSection(value) {
            Object.values(sections).forEach(sec => sec.style.display = 'none');
            if (sections[value]) {
                sections[value].style.display = 'block';
            }
            // التكرار فقط للمهام
            document.getElementById('recurrence-row').style.display = (value === "1") ? '' : 'none';
        }

        assignSelect.addEventListener('change', function () {
            toggleAssignSection(this.value);
        });

        toggleAssignSection(assignSelect.value);

        // التحكم في الأيام (تكرار)
        const recurrenceSelect = document.getElementById('recurrenceType');
        const daysWrapper = document.getElementById('days-wrapper');
        const weeklyDays = document.getElementById('weekly-days');
        const monthlyDays = document.getElementById('monthly-days');

        function toggleDays(value){
            daysWrapper.style.display = 'none';
            weeklyDays.style.display = 'none';
            monthlyDays.style.display = 'none';

            if(value === 'weekly'){
                daysWrapper.style.display = 'block';
                weeklyDays.style.display = 'block';
            }else if(value === 'monthly'){
                daysWrapper.style.display = 'block';
                monthlyDays.style.display = 'block';
            }
        }
        recurrenceSelect.addEventListener('change', function () {
            toggleDays(this.value);
        });
        toggleDays(recurrenceSelect.value);

        function toggleAssignSection(value) {
            Object.values(sections).forEach(sec => sec.style.display = 'none');
            if (sections[value]) {
                sections[value].style.display = 'block';
            }

            // التكرار فقط للمهام
            document.getElementById('recurrence-row').style.display = (value === "1") ? '' : 'none';

            // تحديث task_type حسب الاختيار
            const taskTypeInput = document.getElementById('taskTypeInput');
            if (value === "1") {
                taskTypeInput.value = "App\\Models\\Task";
            } else if (value === "2") {
                taskTypeInput.value = "App\\Models\\ReportItem";
            } else if (value === "3") {
                taskTypeInput.value = "App\\Models\\DailyControlItem";
            } else {
                taskTypeInput.value = "";
            }
        }

    </script>
</x-app-layout>
