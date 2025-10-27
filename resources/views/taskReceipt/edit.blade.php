<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل استلام مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('task_receipts.update', $taskReceipt->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <!-- اختيار الإسناد -->
                <div class="form-group">
                    <label>الإسناد</label>
                    <select name="task_assignment_id" required>
                        <option value="">اختر الإسناد</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ $assignment->id == $taskReceipt->task_assignment_id ? 'selected' : '' }}>
                                {{ $assignment->task->item->name ?? '-' }} # {{ $assignment->created_at ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>وقت الاستلام</label>
                    <input type="datetime-local" name="received_at" value="{{ old('received_at', \Carbon\Carbon::parse($taskReceipt->received_at)->format('Y-m-d\TH:i')) }}" required>
                </div>



                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_completed" value="1" class="checkbox" {{ $taskReceipt->is_completed ? 'checked' : '' }}>
                        مكتملة
                    </label>
                </div>
            </div>

            <div class="row-2">
                <div class="form-group" id="solution_method">
                    <label>طريقة الحل</label>
                    <textarea name="solution_method">{{ old('solution_method', $taskReceipt->solution_method) }}</textarea>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="forwardCheckbox" name="forwarded_to_management" value="1" class="checkbox" {{ $taskReceipt->forwarded_to_management ? 'checked' : '' }}>
                        تحويل للادارة
                    </label>
                </div>
            </div>

            <div class="form-group" id="forwardReasonDiv" style="display: none;">
                <label>سبب التحويل</label>
                <textarea name="forward_reason">{{ old('forward_reason', $taskReceipt->forward_reason) }}</textarea>
            </div>

            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes">{{ old('notes', $taskReceipt->notes) }}</textarea>
            </div>

            <div class="form-group">
                <label>الموقع</label>
                <input type="text" name="location" value="{{ old('location', $taskReceipt->location) }}" placeholder="اكتب الموقع أو العنوان">
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">تحديث</button>
                <a href="{{ route('task_receipts.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>

    <script>
        const forwardCheckbox = document.getElementById('forwardCheckbox');
        const forwardReasonDiv = document.getElementById('forwardReasonDiv');

        function toggleForwardReason() {
            forwardReasonDiv.style.display = forwardCheckbox.checked ? 'block' : 'none';
        }

        forwardCheckbox.addEventListener('change', toggleForwardReason);
        toggleForwardReason(); // عند تحميل الصفحة
    </script>
</x-app-layout>
