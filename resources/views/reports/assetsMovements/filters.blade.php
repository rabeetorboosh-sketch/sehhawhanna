<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير  حركات الاصول  {{$title??''}}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('assetsMovements.'.($url??'byOperation'),$id??'') }}" method="get" enctype="multipart/form-data">
            <div class="row-5">
                <div class="form-group">
                    <label>المستخدم </label>
                    <select name="user_id">
                        <option value="">الكل</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id')==$user->id ? 'selected':'' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الموظف </label>
                    <select name="employee_id">
                        <option value="">الكل</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id')==$employee->id ? 'selected':'' }}>
                                {{ $employee?->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الاصل </label>
                    <select name="asset_id">
                        <option value="">الكل</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ request('asset_id')==$asset->id ? 'selected':'' }}>
                                {{ $asset->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>
            </div>
            <div class="row-5">
                <div class="row-3">
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('assetsMovements.'.($url??'byOperation')) }}" class="btn btn-worn">إعادة تعيين</a>
                    </div>
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('assetsMovements.' . ($urlPrint ?? 'byOperationPrint'), array_merge(['id' => $id ?? ''], request()->query())) }}" class="btn btn-secondary">
                            طباعة
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="main-holder">
        @yield('tbl')
    </div>

    <script src="{{ asset('js/report/filter.js') }}"></script>
    <script src="{{ asset('js/report/tableReport.js') }}"></script>




</x-app-layout>
