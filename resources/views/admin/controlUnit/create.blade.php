<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة وحدة مراقبة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{route('controlUnit.store')}}" method="post">
            @csrf
            <div class="row-2">
                <div class="form-group">
                    <label>اسم وحدة الرقابة </label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>نوع الـمـشـكــلــة</label>
                    <select name="issue_type_id" required>
                        <option disabled selected>اختر نوع المشكلة</option>
                        @foreach($issueTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>الـــــقــــســــــم</label>
                    <select name="department_id" id="department" required>
                        <option disabled selected>اختر القسم</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{($department??'')==$section->id?'selected':''}}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id" id="main_group" required>
                        <option disabled selected>اختر المجموعة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id" id="sub_group" required>
                        <option disabled selected>اختر المجموعة الفرعية</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>التــحـكـم بالـصور</label>
                    <div class="extra-border" style=" border-radius: 10px ;border: 1px solid  ;padding: 5px">
                        <label>
                            <input type="radio" name="has_photos" value="1" class="checkbox radio" checked> إضافة صورة
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="has_photos" value="2" class="checkbox radio"> إضافة صور متعددة
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="has_photos" value="0" class="checkbox radio"> بدون صور
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="daily_control" value="1" class="checkbox">
                        ضمن الرقابة اليومية
                    </label>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
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
