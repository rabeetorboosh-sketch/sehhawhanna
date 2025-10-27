<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة مستخدم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>الاسم</label>
                    <input name="name" type="text" value="{{ old('name') }}" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>الايميل</label>
                    <input name="email" type="email" value="{{ old('email') }}" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input name="password" type="password" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <input name="password_confirmation" type="password" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label>الصلاحية</label>
                    <select name="rule" required>
                        <option disabled selected>اختر الصلاحية</option>
                        <option value="user" {{ old('rule') == 'user' ? 'selected' : '' }}>مستخدم</option>
                        <option value="admin" {{ old('rule') == 'admin' ? 'selected' : '' }}>مسؤول</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <button type="reset" class="btn-primary">إعادة تعيين</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
