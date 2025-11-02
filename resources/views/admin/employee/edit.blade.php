<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل موظف
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row-2">
                <div class="form-group">
                    <label> الاســــــــــــــــــم</label>
                    <input name="name" type="text" value="{{ $employee->item->name }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>   الــنــوع الــوظــيــفـي</label>
                    <select name="type_id" id="type_id">
                        <option disabled >اختر نوع </option>
                        @foreach( $employeeTypes as $employeeType)
                            <option value="{{$employeeType->id}}" {{ $employee->type_id == $employeeType->id ? 'selected' : '' }}>{{$employeeType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> الــجـــنـــســـيــــة</label>
                    <input name="nationality" type="text" value="{{ $employee->nationality }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الــــــــعــــمـــــــر</label>
                    <input name="age" type="number" value="{{ $employee->age }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>رقـــم الــهـــاتــف</label>
                    <input name="phone" type="text" value="{{ $employee->phone }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الايــــمـــــيـــــــل  </label>
                    <input name="email" type="email"  value="{{ $employee->email}}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>الرقــم الـتعـريـفـي</label>
                    <input name="id_number" type="text" value="{{ $employee->id_number }}" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>تاريخ انتهاء الهوية</label>
                    <input type="date" name="id_expiry_date" value="{{ $employee->id_expiry_date }}">
                </div>

                <div class="form-group">
                    <label> ربــط  بـمـسـتـخـدم </label>
                    <select name="user_id">
                        <option   selected>اختر المستخدم</option>
                        @foreach( $users as $user)
                            <option value="{{ $user->id }}" {{ $employee->user_id == $user->id ? 'selected' : '' }}>{{ $user   ->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id" required>
                        <option value=" " disabled  >
                            اختر مجموعة ..
                        </option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{ $mainGroup->id }}" {{ $employee->item->main_group_id == $mainGroup->id ? 'selected' : '' }}>
                                {{ $mainGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id">
                        <option value=" "  >
                     اختر مجموعة ..
                        </option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{ $subGroup->id }}" {{ $employee->item->sub_group_id == $subGroup->id ? 'selected' : '' }}>
                                {{ $subGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ التعديلات</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/form.js') }}"></script>
</x-app-layout>
