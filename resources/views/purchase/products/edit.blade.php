<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل الصنف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{route('pur_items.update', $product->id)}}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اســــــــم الصـــنف</label>
                    <input name="name" type="text" value="{{ $product->name }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>كــــــود الــــصنــف</label>
                    <input name="code" type="text" value="{{ $product->code }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id">
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{$mainGroup->id}}" {{ $product->main_group_id == $mainGroup->id ? 'selected' : '' }}>{{$mainGroup->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id">
                        @foreach($subGroups as $subGroup)
                            <option value="{{$subGroup->id}}" {{ $product->sub_group_id == $subGroup->id ? 'selected' : '' }}>{{$subGroup->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="border:1px solid gray;border-radius:10px;padding:5px;">
                    <h3>الوحدات</h3>
                    <div id="units-wrapper">
                        @foreach($product->units as $index => $unit)
                            <div class="unit-row">
                                <div class="row-3">
                                    <div class="form-group">
                                        <label>الوحدة</label>
                                        <select name="units[{{ $index }}][unit_id]" data-name="unit_id" required>
                                            @foreach($units as $u)
                                                <option value="{{ $u->id }}" {{ $u->id == $unit->unit_id ? 'selected' : '' }}>{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> العبوة</label>
                                        <input type="number" name="units[{{ $index }}][package]" data-name="package" min="1" value="{{ $unit->quantity }}">
                                    </div>
                                    <div class="form-group">
                                        <label>وحدة رئيسية</label>
                                        <input class="checkbox" type="checkbox" name="units[{{ $index }}][is_main]" data-name="is_main" {{ $unit->is_main ? 'checked' : '' }}>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-unit">-</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-worn add-unit">+</button>
                </div>

            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/form.js') }}"></script>

    <script>
        let unitIndex = {{ $product->units->count() }};
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

        // تفعيل الحذف للوحدات الموجودة مسبقاً
        document.querySelectorAll('.remove-unit').forEach((btn) => {
            btn.addEventListener('click', function(){
                btn.closest('.unit-row').remove();
            });
        });
    </script>
</x-app-layout>
