<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل بيانات المخالفة رقم #{{ $violation->id }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('violations.update', $violation->id) }}" method="post">
            @csrf
            @method('PATCH')

            <div class="row-2">
                <div class="form-group">
                    <label>الموظف المخالف</label>
                    <select name="employee_id" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $violation->employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المشرف المسؤول</label>
                    <select name="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $violation->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row-2">
                <div class="form-group">
                    <label>المخالفة</label>
                    <select name="violation_id" required>
                        {{-- مثال للبيانات المخزنة --}}
                        <option value="1" {{ $violation->violation_id == 1 ? 'selected' : '' }}>تأخر عن العمل</option>
                        <option value="2" {{ $violation->violation_id == 2 ? 'selected' : '' }}>سلوك غير مهني</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>تصنيف المخالفة</label>
                    <input type="text" name="violations_type" value="{{ $violation->violations_type }}" required>
                </div>
            </div>

            <h3>تعديل الإرسال والملاحظات</h3>
            <div class="row-2">
                <div class="form-group">
                    <label>الجهات المعنية</label>
                    @php
                        // التأكد من أن sent_to مصفوفة حتى لو كانت فارغة لتجنب أخطاء in_array
                        $sentTo = is_array($violation->sent_to) ? $violation->sent_to : [];
                    @endphp
                    <select name="sent_to[]" multiple class="form-control" style="height: 100px;">
                        <option value="hr" {{ in_array('hr', $sentTo) ? 'selected' : '' }}>الموارد البشرية (HR)</option>
                        <option value="manager" {{ in_array('manager', $sentTo) ? 'selected' : '' }}>مدير القسم</option>
                        <option value="finance" {{ in_array('finance', $sentTo) ? 'selected' : '' }}>المالية</option>
                        <option value="legal" {{ in_array('legal', $sentTo) ? 'selected' : '' }}>الشؤون القانونية</option>
                    </select>
                    <small>* يمكنك إعادة اختيار الجهات</small>
                </div>

                <div class="form-group">
                    <label>ملاحظات إضافية</label>
                    <textarea name="note" rows="4">{{ $violation->note }}</textarea>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('violations.index') }}" class="btn-primary" style="text-decoration: none; text-align: center; line-height: 2;">إلغاء</a>
                <button type="submit" class="btn-save">تحديث البيانات</button>
            </div>
        </form>
    </div>
</x-app-layout>
