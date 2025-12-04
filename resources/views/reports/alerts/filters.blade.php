<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير الرقابة اليومية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('ratingReport.index') }}" method="get">

            {{-- الصف الأول --}}
            <div class="row-3">
                <div class="form-group">
                    <label>من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>

                <div class="form-group">
                    <label>البند</label>
                    <input type="text" id="itemInput" placeholder="ابحث عن بند"
                           value="{{ optional($items->where('id', request('item_id'))->first())->name ?? 'كل البنود' }}">
                    <input type="hidden" name="item_id" id="itemId" value="{{ request('item_id') }}">
                    <select id="itemSelect" size="5" class="item-search">
                        <option value="">كل البنود</option>
                        @foreach ($items as $i)
                            <option value="{{ $i->id }}" {{ request('item_id') == $i->id ? 'selected' : '' }}>
                                {{ $i->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- الصف الثاني --}}
            <div class="row-3">
                <div class="form-group">
                    <label>القسم</label>
                    <select name="department_id" id="departmentSelect">
                        <option value="">كل الأقسام</option>
                        @foreach ($departments as $dep)
                            <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group_id" id="mainGroupSelect">
                        <option value="">اختر المجموعة الرئيسية</option>
                        @foreach ($mainGroups as $main)
                            <option value="{{ $main->id }}"
                                    data-department="{{ $main->department->id }}"
                                {{ request('main_group_id') == $main->id ? 'selected' : '' }}>
                                {{ $main->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group_id" id="subGroupSelect">
                        <option value="">اختر المجموعة الفرعية</option>
                        @foreach ($subGroups as $sub)
                            <option value="{{ $sub->id }}"
                                    data-main-group="{{ $sub->main_group_id }}"
                                {{ request('sub_group_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- الصف الثالث --}}
            <div class="row-5">

                <div class="form-group">
                    <label>الوحدة الرقابية</label>
                    <select name="control_unit_id" id="controlUnitSelect">
                        <option value="">فلترة حسب الوحدة</option>
                        @foreach ($controlUnits as $unit)
                            <option value="{{ $unit->id }}"
                                    data-photo="{{ $unit->has_photos }}"
                                    data-department="{{ $unit->department_id }}"
                                    data-main-group="{{ $unit->main_group_id }}"
                                    data-sub-group="{{ $unit->sub_group_id }}"
                                {{ request('control_unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المتسبب</label>
                    <select name="causer_id">
                        <option value="">الكل</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('causer_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->item->name ?? 'بدون اسم' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>المشاكل</label>
                    <select name="issues">
                        <option value="0" {{ request('issues') == 0 ? 'selected' : '' }}>الكل</option>
                        <option value="1" {{ request('issues') == 1 ? 'selected' : '' }}>المشاكل فقط</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>طريقة العرض</label>
                    <select name="summary">
                        <option value="0" {{ request('summary') == 0 ? 'selected' : '' }}>تحليلي</option>
                        <option value="1" {{ request('summary') == 1 ? 'selected' : '' }}>إجمالي</option>
                    </select>
                </div>

                <div class="row-3">
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('ratingReport.index') }}" class="btn btn-worn">إعادة تعيين</a>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('ratingReport.index', request()->query()) }}&print=1"
                           class="btn btn-secondary">طباعة</a>
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
