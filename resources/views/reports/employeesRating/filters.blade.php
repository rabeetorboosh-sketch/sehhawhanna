<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير التقييمات {{ $title ?? '' }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('ratingReport.' . ($url ?? 'byOperationDetail'), $id ?? '') }}" method="get">
            <div class="row-5">

                {{-- المستخدم --}}
                <div class="form-group">
                    <label>المستخدم</label>
                    <select name="user_id">
                        <option value="">الكل</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- العنصر --}}
                <div class="form-group">

                    <label>الموظف</label>
                    <select name="item_id">
                        <option value="">الكل</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- من تاريخ --}}
                <div class="form-group">
                    <label>من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>

                {{-- إلى تاريخ --}}
                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>

                {{-- نوع التقرير --}}
                <div class="form-group">
                    <label>طريقة العرض</label>
                    <select name="summary">
                        <option value="">تحليلي</option>
                        <option value="1" {{ request('summary') == 1 ? 'selected' : '' }}>إجمالي</option>
                    </select>
                </div>
            </div>

            <div class="row-5">
                <div class="row-3">
                    {{-- زر الفلترة --}}
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>

                    {{-- زر إعادة التعيين --}}
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('ratingReport.' . ($url ?? 'byOperationDetail')) }}" class="btn btn-worn">إعادة تعيين</a>
                    </div>

                    {{-- زر الطباعة --}}
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('ratingReport.' . ($urlPrint ?? 'byOperationDetailPrint'), array_merge(['id' => $id ?? ''], request()->query())) }}" class="btn btn-secondary">
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
