<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة حل طلب صيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('maintenance_solutions.store') }}" method="post">
            @csrf
<input type="hidden"  name="maintenance_request_id" value="{{$request?? ""}}">
            <div class="row-2">

                <div class="form-group">
                    <label>سبب المشكلة</label>
                    <textarea name="issue_reason" required></textarea>
                </div>

                <div class="form-group">
                    <label>حل المشكلة</label>
                    <textarea name="solution_text"></textarea>
                </div>

                <div class="form-group">
                    <label style="display:inline-block; width: 70%">الوقت المستغرق لحل المشكلة (ساعات)</label>
                    <input type="number" name="time_spent" step="0.1" min="0" style="width: 28%;">
                </div>

                <div class="form-group">
                    <label>القطع الغير صالحة</label>
                    <textarea name="bad_parts"></textarea>
                </div>

                <div class="form-group">
                    <label>اسم الورشة</label>
                    <input type="text" name="workshop_name">
                </div>

                <div class="form-group">
                    <label>اسم مسئول الصيانة</label>
                    <input type="text" name="maintenance_responsible">
                </div>

                <div class="form-group">
                    <label>تكلفة الإصلاح</label>
                    <input type="number" name="repair_cost" step="0.01" min="0">
                </div>

                <div class="form-group">
                    <label>هل الحل مؤقت؟</label>
                    <input type="checkbox" name="temporary_solution" value="1" class="checkbox">
                </div>

                <!-- قسم الضمان مع Navbar داخلي -->
                <div class="form-group">
                    <label>الضمان</label>
                    <div class="warranty-tabs">
                        <span class="tab-button active tab" data-tab="no-warranty" id="no-warranty-btn" style="display: none">لا يوجد</span>
                        <span class="tab-button tab" data-tab="has-warranty" id="add-warranty-btn">اضافة ضمان</span>
                    </div>

                    <input type="hidden" name="has_warranty" id="has_warranty" value="0">

                    <div id="has-warranty" class="tab-content" style="display:none;">
                        <div class="form-group">
                            <label>نوع الضمانة</label>
                            <input type="text" name="warranty_type">
                        </div>
                        <div class="form-group">
                            <label>صلاحية الضمان</label>
                            <input type="date" name="warranty_expiry">
                        </div>
                    </div>
                </div>





                <div class="form-group">
                    <label>هل تم تسليمها؟</label>
                    <input type="checkbox" name="delivered" value="1" class="checkbox">
                </div>

            </div>

            <div class="actions">
                <a href="{{ route('maintenance_solutions.index') }}" class="btn btn-secondary">رجوع</a>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>

    <script>
        const noWarrantyBtn = document.getElementById('no-warranty-btn');
        const addWarrantyBtn = document.getElementById('add-warranty-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const hasWarrantyInput = document.getElementById('has_warranty');

        addWarrantyBtn.addEventListener('click', () => {
            addWarrantyBtn.style.display = 'none'; // إخفاء زر الإضافة
            noWarrantyBtn.style.display = 'inline'; // عرض زر لا يوجد
            tabContents.forEach(content => {
                if(content.id === 'has-warranty') content.style.display = 'block';
            });
            hasWarrantyInput.value = '1';
        });

        noWarrantyBtn.addEventListener('click', () => {
            addWarrantyBtn.style.display = 'inline'; // عرض زر الإضافة
            noWarrantyBtn.style.display = 'none'; // زر لا يوجد يبقى ظاهر
            tabContents.forEach(content => {
                if(content.id === 'has-warranty') content.style.display = 'none';
            });
            hasWarrantyInput.value = '0';
        });
    </script>
</x-app-layout>
