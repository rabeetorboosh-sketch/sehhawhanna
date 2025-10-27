<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تقارير المشرفين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('reportSupervisors.'.($url??'index')) }}" method="get" enctype="multipart/form-data">
            <div class="row-5">
                <div class="form-group">
                    <label><i class="fa-solid fa-user-tag"></i> اسم العميل</label>
                    <input type="text"
                           class="searcher"
                           placeholder="ابحث عن العميل"
                           value="{{ optional($customers->where('id', request('customer_id'))->first())->item?->name }}">
                    <select name="customer_id" id="clientSelect" size="5" class="client-search" style="max-height: 0 ;padding: 0">
                        @foreach($customers as $clnt)
                            <option value="{{ $clnt->id }}"
                                    @if(request('customer_id') == $clnt->id || (isset($issue) && $issue->client_id == $clnt->id)) selected @endif>
                                {{ $clnt->item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>اسم المشرف</label>
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
                    <label>من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}">
                </div>
            </div>

            <div class="row-5">
                <div class="form-group">
                    <label>المنجزة</label>
                    <select name="corrected">
                        <option value="">الكل</option>
                        <option value="1" {{ request('corrected')=='1' ? 'selected':'' }}>المنجزة فقط</option>
                        <option value="0" {{ request('corrected')=='0' ? 'selected':'' }}>الغير منجزة فقط</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>التحويل للإدارة</label>
                    <select name="is_trans">
                        <option value="">الكل</option>
                        <option value="1" {{ request('is_trans')=='1' ? 'selected':'' }}>المحولة فقط</option>
                        <option value="0" {{ request('is_trans')=='0' ? 'selected':'' }}>الغير محولة فقط</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>  طريقة العرض </label>
                    <select name="summary">
                        <option value="" >تحليلي</option>
                        <option value="1" {{ request('summary')==1? 'selected':'' }}>اجمالي</option>
                    </select>
                </div>
                <div class="row-3">
                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('reportSupervisors.index') }}" class="btn btn-worn">إعادة تعيين</a>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent">-</label>
                        <a href="{{ route('reportSupervisors.'.($urlPrint??'print'), request()->query()) }}" class="btn btn-secondary">طباعة</a>
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
