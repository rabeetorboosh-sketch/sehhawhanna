<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة صنف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{route('pur_items.store')}}" method="post">
            @csrf

            <div class="row-2">
                <div>


                    <div class="form-group">
                        <label>اســــــــم الصـــنف</label>
                        <input name="name" type="text" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>كــــــود الــــصنــف</label>
                        <input name="code" type="text" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>المجموعة الرئيسية</label>
                        <select name="main_group_id">
                            <option disabled selected>اختر المجموعة</option>
                            @foreach($mainGroups as $mainGroup)
                                <option value="{{$mainGroup->id}}">{{$mainGroup->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>المجموعة الفرعية</label>
                        <select name="sub_group_id">
                            <option disabled selected>اختر المجموعة الفرعية</option>
                            @foreach($subGroups as $subGroup)
                                <option value="{{$subGroup->id}}">{{$subGroup->name}}</option>
                            @endforeach
                        </select>
                    </div>




                </div>


                <div class="form-group" style=" border: 1px solid gray;border-radius: 10px ;padding: 5px">

                    <div id="units-wrapper">
                        <div class="unit-row">
                            <div class="row-3">
                                <div class="form-group">
                                    <label>الوحدة</label>
                                    <select name="units[0][unit_id]" data-name="unit_id" required>

                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label> العبوة</label>
                                    <input type="number" name="units[0][package]" data-name="package" min="1" value="1">
                                </div>
                                <div class="row-2">
                                <div class="form-group">
                                    <label>وحدة رئيسية</label>
                                    <input class="checkbox" type="checkbox" name="units[0][is_main]" data-name="is_main">
                                </div>
                                <button type="button" class="btn btn-danger remove-unit">-</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <button type="button" class="btn btn-worn add-unit">+</button>
                </div>

            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/form.js') }}"></script>
    <script>
        let unitIndex = 1;
        document.querySelector('.add-unit').addEventListener('click', function(){
            let wrapper = document.getElementById('units-wrapper');
            let row = document.querySelector('.unit-row');
            let clone = row.cloneNode(true);

            clone.querySelectorAll('select, input[type="number"], input[type="checkbox"]').forEach((input) => {
                let originalName = input.getAttribute('data-name');
                input.name = `units[${unitIndex}][${originalName}]`;
                if(input.type !== 'checkbox') input.value = '';
                else input.checked = false;
            });

            // زر الحذف
            clone.querySelector('.remove-unit').addEventListener('click', function(){
                clone.remove();
            });

            wrapper.appendChild(clone);
            unitIndex++;
        });

        // تفعيل الحذف للوحدة الأولى إذا تكررت
        document.querySelectorAll('.remove-unit').forEach((btn) => {
            btn.addEventListener('click', function(){
                btn.closest('.unit-row').remove();
            });
        });
    </script>
</x-app-layout>
