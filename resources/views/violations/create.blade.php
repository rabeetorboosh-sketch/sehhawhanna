<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تسجيل مخالفة جديدة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('violations.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الموظف المخالف</label>
                    <select name="employee_id" required>
                        <option value="">اختر الموظف</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->item->name }}</option>
                        @endforeach
                    </select>
                </div>


            </div>

            <div class="row-2">
                <div class="form-group">
                    <label>المخالفة</label>
                    <select name="violation_id" required>
                        <option value="">اختر نوع المخالفة</option>
                        {{-- هنا تضع قائمة أنواع المخالفات من جدول المخالفات لديك --}}
                        <option value="1"> بلاغ </option>
                        <option value="2">  رقابة يومية </option>
                        <option value="2"> مباشرة </option>
                    </select>
                </div>


            </div>


            <div class="row-2">
                <div class="form-group">
                    <label>إرسال إشعار إلى</label>
                    <select name="sent_to[]" multiple class="form-control" style="height: 100px;">
                        @foreach($users as $user)

                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach

                    </select>
                    <small style="color: #666;">* اضغط Ctrl لاختيار أكثر من جهة</small>
                </div>

                <div class="form-group">
                    <label>ملاحظات إضافية</label>
                    <textarea name="note" rows="4" placeholder="اكتب تفاصيل المخالفة هنا..."></textarea>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save" style="background-color: #e53e3e;">حفظ المخالفة</button>
            </div>
        </form>
    </div>

    {{-- إذا كان لديك ملف JS خاص بالتعامل مع العمليات الحسابية للمخالفات --}}
    @if(file_exists(public_path('js/violation.js')))
        <script src="{{asset('js/violation.js')}}"></script>
    @endif
</x-app-layout>
