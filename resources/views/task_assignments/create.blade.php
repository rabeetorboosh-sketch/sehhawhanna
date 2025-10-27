<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة إسناد مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('task_assignments.store') }}" method="post">
            @csrf

            <div class="row-2">
                <!-- اختيار نوع الإسناد -->
                <div class="form-group">
                    <label>اسناد من:</label>
                    <select name="assign_type" id="assignType" required>
                        <option value="">اختر المصدر</option>
                        <option value="1">مهام</option>
                        <option value="2">بلاغات</option>
                        <option value="3">رقابة يومية</option>
                    </select>
                </div>

                <!-- المهام -->
                <div class="form-group assign-section" id="tasks-section" style="display:none;">
                    <label>المهمة</label>
                    <div class="search-filter">
                        <input type="text" id="task-name" placeholder="ابحث عن مهمة " class="name-searcher">
                        @foreach($tasks as $task)
                        <div class="item-card">
                            <label>
                                <input type="checkbox" class="itemsCheck checkbox"  name="tasks[]" value="{{$task->id}}"><span>{{$task->item->name }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <span class="btn btn-secondary">
                        <a href="{{route('tasks.create')}}">اضافة مهمة + </a>
                    </span>
                </div>

                <!-- البلاغات -->
                    <div class="form-group assign-section" id="reports-section" style="display:none;">
                        <label>البلاغ</label>
                        <div class="search-filter">
                            <input type="text" id="report-name" placeholder="ابحث عن بلاغ " class="name-searcher">
                            <input id="report-date" type="date" class="name-searcher">


                            @foreach($reports as $report)
                                @foreach($report->items as $item)
                                    @if($item->control_unit_id!=null)
                                        <div class="item-card">
                                            <label>
                                                <input type="checkbox" data-date="{{$item->created_at}}" class="itemsCheck checkbox"  name="reports[]" value="{{$item->id}}"><span>  {{$item->controlUnit->name .'->'. $item->item?->name }}</span>
                                            </label>
                                        </div>
                                    @elseif($item->user_control_unit!=null)

                                        <div class="item-card">
                                            <label>
                                                <input type="checkbox" data-date="{{$item->created_at}} "   class="itemsCheck checkbox"  name="reports[]" value="{{$item->id}}"><span> {{$item->user_control_unit .'->'. $item->item?->name}}</span>
                                            </label>
                                        </div>

                                    @endif
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
                                @if($item->dailyControl_id != null)
                                    <div class="item-card">
                                        <label>
                                            <input type="checkbox"
                                                   data-date="{{ $item->created_at }}"
                                                   class="itemsCheck checkbox"
                                                   name="monitorings[]"
                                                   value="{{ $item->id }}">
                                            <span>{{ $item->controlUnit->name .' -> '. $item->item?->name }}</span>
                                        </label>
                                    </div>
                                @endif
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
                            <option value="{{ $emp->id }}">{{ $emp->item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- وقت الإسناد -->
            <div class="row-2">
                <div class="form-group">
                    <label>وقت الإسناد</label>
                    <input type="datetime-local" name="assigned_at" required>
                </div>
                <div class="form-group">
                    <label>آخر موعد لإنجاز المهام</label>
                    <input type="datetime-local" name="due_date">
                </div>
            </div>

            <!-- التكرار -->
            <div class="row-2">
                <div class="form-group">
                    <label>نوع التكرار</label>
                    <select name="recurrence_type" id="recurrenceType">
                        <option value="">بدون تكرار</option>
                        <option value="daily">يومي</option>
                        <option value="weekly">أسبوعي</option>
                        <option value="monthly">شهري</option>
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
                                <input type="checkbox" name="days[]" value="{{ $num }}" id="day{{ $num }}" class="checkbox">
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
                                <input type="checkbox" class="checkbox" name="days[]" value="{{ $i }}" id="mday{{ $i }}">
                                <label for="mday{{ $i }}">{{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- الأزرار -->
            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>

    <script>
        // التحكم في ظهور المهام / البلاغات / الرقابة
        const assignSelect = document.getElementById('assignType');
        const sections = {
            "1": document.getElementById('tasks-section'),
            "2": document.getElementById('reports-section'),
            "3": document.getElementById('monitorings-section')
        };

        assignSelect.addEventListener('change', function () {
            Object.values(sections).forEach(sec => sec.style.display = 'none');
            if (sections[this.value]) {
                sections[this.value].style.display = 'block';
            }
        });

        // التحكم في الأيام (تكرار)
        const recurrenceSelect = document.getElementById('recurrenceType');
        const daysWrapper = document.getElementById('days-wrapper');
        const weeklyDays = document.getElementById('weekly-days');
        const monthlyDays = document.getElementById('monthly-days');

        recurrenceSelect.addEventListener('change', function () {
            daysWrapper.style.display = 'none';
            weeklyDays.style.display = 'none';
            monthlyDays.style.display = 'none';

            if (this.value === 'weekly') {
                daysWrapper.style.display = 'block';
                weeklyDays.style.display = 'block';
            } else if (this.value === 'monthly') {
                daysWrapper.style.display = 'block';
                monthlyDays.style.display = 'block';
            }
        });
        const recurrenceRow = document.querySelector('.row-2 .form-group select[name="recurrence_type"]').closest('.row-2');

        assignSelect.addEventListener('change', function () {
            Object.values(sections).forEach(sec => sec.style.display = 'none');
            if (sections[this.value]) {
                sections[this.value].style.display = 'block';
            }

            // التكرار يظهر فقط مع المهام
            if (this.value === "1") {
                recurrenceRow.style.display = ''; // أو block حسب التصميم
            } else {
                recurrenceRow.style.display = 'none';
                daysWrapper.style.display = 'none';
                weeklyDays.style.display = 'none';
                monthlyDays.style.display = 'none';
            }
        });

        // عند تحميل الصفحة أول مرة
        if (assignSelect.value !== "1") {
            recurrenceRow.style.display = 'none';
            daysWrapper.style.display = 'none';
        }


        function initFilter(sectionId, textInputId, dateInputId) {
            const section = document.getElementById(sectionId);
            const textInput = document.getElementById(textInputId);
            const dateInput = document.getElementById(dateInputId);

            function filterItems() {
                const textValue = textInput ? textInput.value.toLowerCase() : "";
                const dateValue = dateInput ? dateInput.value : "";
                const items = section.querySelectorAll(".item-card");

                items.forEach(item => {
                    const itemText = item.innerText.toLowerCase();
                    const itemDate = item.querySelector("input[type=checkbox]").dataset.date || "";

                    let textMatch = textValue === "" || itemText.includes(textValue);
                    let dateMatch = dateValue === "" || itemDate.startsWith(dateValue);

                    item.style.display = (textMatch && dateMatch) ? "block" : "none";
                });
            }

            if (textInput) textInput.addEventListener("keyup", filterItems);
            if (dateInput) dateInput.addEventListener("change", filterItems);
        }

        // تشغيل الفلترة على المهمات
        initFilter("tasks-section", "task-name", null);

        // تشغيل الفلترة على البلاغات
        initFilter("reports-section", "report-name", "report-date");
        initFilter("monitorings-section", "monitoring-name", "monitoring-date");



        // تحديد الكل / إلغاء التحديد لأي سكشن
        function toggleAll(sectionId, checked) {
            const checkboxes = document.querySelectorAll(`#${sectionId} .itemsCheck`);
            checkboxes.forEach(cb => cb.checked = checked);
        }
    </script>
</x-app-layout>
