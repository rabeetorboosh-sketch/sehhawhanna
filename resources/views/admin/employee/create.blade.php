<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة موظف
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="row-2">
                <div class="form-group">
                    <label>الاســــــــــــــــــم</label>
                    <input name="name" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>   الــنــوع الــوظــيــفـي</label>
                    <select name="type_id" id="type_id">
                        <option disabled >اختر نوع </option>
                        @foreach( $employeeTypes as $employeeType)
                            <option value="{{$employeeType->id}}">{{$employeeType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الــجـــنـــســـيــــة</label>
                    <input name="nationality" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الــــــــعــــمـــــــر</label>
                    <input name="age" type="number" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>رقـــم الــهـــاتــف</label>
                    <input name="phone" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الايــــمـــــيـــــــل  </label>
                    <input name="email" type="email" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الرقــم الـتعـريـفـي</label>
                    <input name="id_number" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الــــتـــوقــــيــع  </label>
                    <input name="signature" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>تاريخ انتهاء الهوية</label>
                    <input type="date" name="id_expiry_date">
                </div>
                <div class="form-group">
                    <label> ربــط  بـمـسـتـخـدم </label>
                    <select name="user_id">
                        <option value=""  selected>اختر المستخدم</option>
                        @foreach( $users as $user)
                            <option value="{{ $user->id }}">{{ $user   ->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group" id="main_group" required>
                        <option disabled  >اختر المجموعة</option>
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
                <button type="reset" class="btn btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
