<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('tasks.update', $task->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>اســـم الــمــهـمـة</label>
                    <input name="name" type="text" value="{{ $task->item->name }}" required>
                </div>
                <div class="form-group">
                    <label>وصـــف الـمـهـمـة</label>
                    <input name="description" type="text"  value="{{ $task->description }}" autocomplete="off" >
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id">
                        <option value="">اختر</option>
                        @foreach($mainGroups as $group)
                            <option value="{{ $group->id }}" {{ $task->item->main_group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id">
                        <option  value="">اختر</option>
                        @foreach($subGroups as $group)
                            <option value="{{ $group->id }}" {{ $task->item->   sub_group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="submit" > </button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>
        </form>
        <script src="{{asset('js/form.js')}}"></script>
    </div>
</x-app-layout>
