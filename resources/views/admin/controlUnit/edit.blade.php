<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل وحدة مراقبة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('controlUnit.update', $controlUnit->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اسم وحدة الرقابة </label>
                    <input type="text" name="name" value="{{ old('name', $controlUnit->name) }}" required>
                </div>

                <div class="form-group">
                    <label>نوع الـمـشـكــلــة</label>
                    <select name="issue_type_id" required>
                        <option disabled>اختر نوع المشكلة</option>
                        @foreach($issueTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ $controlUnit->issue_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>الـــــقــــســــــم</label>
                    <select name="department_id" id="department" required>
                        <option disabled>اختر القسم</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}"
                                {{ $controlUnit->department_id == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id" id="main_group" required>
                        <option disabled>اختر المجموعة</option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{ $mainGroup->id }}"
                                {{ $controlUnit->main_group_id == $mainGroup->id ? 'selected' : '' }}>
                                {{ $mainGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id" id="sub_group" required>
                        <option disabled>اختر المجموعة الفرعية</option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{ $subGroup->id }}"
                                {{ $controlUnit->sub_group_id == $subGroup->id ? 'selected' : '' }}>
                                {{ $subGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>التــحـكـم بالـصور</label>
                    <div class="extra-border" style="border-radius: 10px; border: 1px solid; padding: 5px">
                        <label>
                            <input type="radio" name="has_photos" value="1" class="checkbox radio"
                                {{ $controlUnit->has_photos == 1 ? 'checked' : '' }}>
                            إضافة صورة
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="has_photos" value="2" class="checkbox radio"
                                {{ $controlUnit->has_photos == 2 ? 'checked' : '' }}>
                            إضافة صور متعددة
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="has_photos" value="0" class="checkbox radio"
                                {{ $controlUnit->has_photos == 0 ? 'checked' : '' }}>
                            بدون صور
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="daily_control" value="1" class="checkbox"
                            {{ $controlUnit->daily_control ? 'checked' : '' }}>
                        ضمن الرقابة اليومية
                    </label>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('controlUnit.index') }}" class="btn btn-primary">إلغاء</a>
                <button type="submit" class="btn btn-save">تحديث</button>
            </div>
        </form>
    </div>

    <script>
        // عند اختيار قسم

        function changer() {

            let departmentId =  document.getElementById('department').value;
            let mainGroupSelect = document.getElementById('main_group');
            let subGroupSelect = document.getElementById('sub_group');

            // تنظيف القوائم
            mainGroupSelect.innerHTML = '<option disabled selected>اختر المجموعة</option>';
            subGroupSelect.innerHTML = '<option disabled selected>اختر المجموعة الفرعية</option>';

            if (departmentId) {
                fetch(`/maingroups/${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(mainGroup => {
                            let option = document.createElement('option');
                            option.value = mainGroup.id;
                            option.textContent = mainGroup.name;
                            mainGroupSelect.appendChild(option);
                        });
                    });
            }
        }
        changer();
        document.getElementById('department').addEventListener('change',changer );

        // عند اختيار مجموعة رئيسية
        document.getElementById('main_group').addEventListener('change', function () {
            let mainGroupId = this.value;
            let subGroupSelect = document.getElementById('sub_group');

            subGroupSelect.innerHTML = '<option disabled selected>اختر المجموعة الفرعية</option>';

            if (mainGroupId) {
                fetch(`/subgroups/${mainGroupId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subGroup => {
                            let option = document.createElement('option');
                            option.value = subGroup.id;
                            option.textContent = subGroup.name;
                            subGroupSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</x-app-layout>
