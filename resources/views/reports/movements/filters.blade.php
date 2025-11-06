<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير  حركات المخزون {{$title??''}}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('storeMovements.'.($url??'byOperationDetail'),$id??'') }}" method="get" enctype="multipart/form-data">
            <div class="row-5">

@if(($url??'byOperationDetail')==='byStoreDetail')
                    <div class="form-group">
                        <label>المخزن  </label>
                        <select name="store_id">
                            <option value="">الكل</option>
                            @foreach($filterstores as $store)
                                <option value="{{ $store->id }}" {{ request('store_id')==$store->id ? 'selected':'' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>



@endif
    @if(($url??'byOperationDetail')!='byOperationDetail')
    <div class="form-group">
        <label>العملية </label>
        <select name="move_id">
            <option value="">الكل</option>
            @foreach($movements as $move)
                <option value="{{ $move->id }}" {{ request('move_id')==$move->id ? 'selected':'' }}>
                    {{ $move->name }}
                </option>
            @endforeach
        </select>
    </div>
    @endif
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
                    <label>الصنف </label>
                    <select name="product_id">
                        <option value="">الكل</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id')==$product->id ? 'selected':'' }}>
                                {{ $product->item?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
    @if($main_groups)
        <div class="form-group">
            <label  >المجموعة الرئيسية</label>
            <select name="main_group_id" id="mainGroupSelect">
                <option selected value=""> الكل </option>
                @foreach($main_groups as $mainGroup)
                    <option value="{{ $mainGroup->id }}" data-section="{{ $mainGroup->department->id }}" {{ request('main_group_id')==$mainGroup->id ? 'selected':'' }}>
                        {{ $mainGroup->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
    @if($sub_groups)
        <div class="form-group">
            <label>المجموعة الفرعية</label>
            <select name="sub_group_id" id="subGroupSelect">
                <option value="" selected> الكل </option>
                @foreach($sub_groups as $subGroup)
                    <option value="{{ $subGroup->id }}" data-main-group="{{ $subGroup->main_group_id }}" {{ request('sub_group_id')==$subGroup->id ? 'selected':'' }}>
                        {{ $subGroup->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
                <div class="form-group">
                    <label>من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="form-group">
                    <label>  طريقة العرض </label>
                    <select name="summary">
                        <option value="" >تحليلي</option>
                        <option value="1" {{ request('summary')==1? 'selected':'' }}>اجمالي</option>
                    </select>
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
                        <a href="{{ route('storeMovements.'.($url??'byOperationDetail')) }}" class="btn btn-worn">إعادة تعيين</a>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('storeMovements.' . ($urlPrint ?? 'byStoreDetailPrint'), array_merge(['id' => $id ?? ''], request()->query())) }}" class="btn btn-secondary">
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
