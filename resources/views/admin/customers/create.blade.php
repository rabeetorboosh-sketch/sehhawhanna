<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضــافــة عـمـيـل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row-2">
                <div class="form-group">
                    <label>الاســــــــــــــــــم</label>
                    <input name="name" type="text" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>الـــــهــــاتـــــــف</label>
                    <input name="phone" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tie"></i> الـــــمـــــوظــــــف</label>
                    <select name="employee_id">
                        <option disabled selected>اختر الموظف</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                    @if(old('employee_id') == $emp->id ) selected @endif>
                                {{ $emp->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tie"></i> الـــــــــخـــــــــط </label>
                    <select name="sales_rout_id">
                        <option disabled selected>اختر الخط</option>
                        @foreach($salerouts as $salerout)
                            <option value="{{ $salerout->id }}"
                                    @if(old('sales_rout_id') == $salerout->id ) selected @endif>
                                {{ $salerout->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group" id="main_group">
                        <option disabled selected>اختر المجموعة</option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{$mainGroup->id}}">{{$mainGroup->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group" id="sub_group">
                        <option disabled selected>اختر المجموعة الفرعية</option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{$subGroup->id}}">{{$subGroup->name}}</option>
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

    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
