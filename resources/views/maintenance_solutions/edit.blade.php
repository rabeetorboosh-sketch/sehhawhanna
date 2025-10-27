<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل حل طلب صيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('maintenance_solutions.update', $solution->id) }}" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="maintenance_request_id" value="{{ $solution->maintenance_request_id }}">

            <div class="row-2">

                <div class="form-group">
                    <label>سبب المشكلة</label>
                    <textarea name="issue_reason" required>{{ $solution->issue_reason }}</textarea>
                </div>

                <div class="form-group">
                    <label>حل المشكلة</label>
                    <textarea name="solution_text">{{ $solution->solution_text }}</textarea>
                </div>

                <div class="form-group">
                    <label>الوقت المستغرق لحل المشكلة (ساعات)</label>
                    <input type="number" name="time_spent" step="0.1" min="0" value="{{ $solution->time_spent }}">
                </div>

                <div class="form-group">
                    <label>القطع الغير صالحة</label>
                    <textarea name="bad_parts">{{ $solution->bad_parts }}</textarea>
                </div>

                <div class="form-group">
                    <label>اسم الورشة</label>
                    <input type="text" name="workshop_name" value="{{ $solution->workshop_name }}">
                </div>

                <div class="form-group">
                    <label>اسم مسئول الصيانة</label>
                    <input type="text" name="maintenance_responsible" value="{{ $solution->maintenance_responsible }}">
                </div>

                <div class="form-group">
                    <label>تكلفة الإصلاح</label>
                    <input type="number" name="repair_cost" step="0.01" min="0" value="{{ $solution->repair_cost }}">
                </div>

                <div class="form-group">
                    <label>هل الحل مؤقت؟</label>
                    <input type="checkbox" name="temporary_solution" value="1" class="checkbox" {{ $solution->temporary_solution ? 'checked' : '' }}>
                </div>

                <!-- قسم الضمان مع Navbar داخلي -->
                <div class="form-group">
                    <label>الضمان</label>
                    <div class="warranty-tabs">
                        <span class="tab-button btn tab {{ !$solution->has_warranty ? 'active' : '' }}" data-tab="no-warranty">لا يوجد</span>
                        <span class="tab-button btn tab {{ $solution->has_warranty ? 'active' : '' }}" data-tab="has-warranty">يوجد</span>
                    </div>

                    <input type="hidden" name="has_warranty" id="has_warranty" value="{{ $solution->has_warranty ? '1' : '0' }}">

                    <div id="has-warranty" class="tab-content" style="display: {{ $solution->has_warranty ? 'block' : 'none' }}">
                        <div class="form-group">
                            <label>نوع الضمانة</label>
                            <input type="text" name="warranty_type" value="{{ $solution->warranty_type }}">
                        </div>
                        <div class="form-group">
                            <label>صلاحية الضمان</label>
                            <input type="date" name="warranty_expiry" value="{{ $solution->warranty_expiry }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>هل تم تسليمها؟</label>
                    <input type="checkbox" name="delivered" value="1" class="checkbox" {{ $solution->delivered ? 'checked' : '' }}>
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <a href="{{ route('maintenance_solutions.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const hasWarrantyInput = document.getElementById('has_warranty');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.dataset.tab;

                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                tabContents.forEach(content => {
                    content.style.display = content.id === target ? 'block' : 'none';
                });

                hasWarrantyInput.value = (target === 'has-warranty') ? '1' : '0';
            });
        });
    </script>
</x-app-layout>
