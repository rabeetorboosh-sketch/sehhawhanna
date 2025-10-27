<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('tasks.store') }}" method="post">
            @csrf

            <div class="row-2">
                <div class="form-group">
                    <label>اســـم الــمــهـمـة</label>
                    <input name="name" type="text" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label>وصـــف الـمـهـمـة</label>
                    <input name="description" type="text" autocomplete="off" >
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id" required>
                        @foreach($mainGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id">
                        <option disabled selected>اختر</option>
                        @foreach($subGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
</x-app-layout>
