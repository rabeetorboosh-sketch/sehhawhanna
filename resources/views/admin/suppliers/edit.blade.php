<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل مورد
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row-2">
                <div class="form-group">
                    <label>الاســــــــــــــــــم</label>
                    <input name="name" type="text" value="{{ $supplier->item->name }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>رقــــــم الــهــاتـف</label>
                    <input name="phone" type="text" value="{{ $supplier->phone }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id">
                        <option disabled>اختر المجموعة</option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{ $mainGroup->id }}" {{ $supplier->item->main_group_id == $mainGroup->id ? 'selected' : '' }}>
                                {{ $mainGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id">
                        <option   value=" ">اختر المجموعة الفرعية</option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{ $subGroup->id }}" {{ $supplier->item->sub_group_id == $subGroup->id ? 'selected' : '' }}>
                                {{ $subGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('suppliers.index') }}" class= "btn btn-primary">إلغاء</a>
                <button type="submit" class="btn btn-save">حفظ</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
