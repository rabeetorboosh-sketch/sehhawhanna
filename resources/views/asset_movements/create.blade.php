<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إضافة حركة أصل جديدة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supervisors.css') }}">

    <div class="py-12">
        <form class="smart-form" method="POST" action="{{ route('asset_movements.store') }}">
            @csrf

            <!-- ===== الأصل ===== -->
            <div class="form-group">
                <label><i class="fa-solid fa-warehouse"></i> الأصل</label>
                <input type="text" id="assetInput" placeholder="ابحث عن الأصل">
                <input type="hidden" name="asset_id" id="assetId" required>
                <select id="assetSelect" size="5" class="asset-search" required>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">
                            {{ $asset->item->name ?? 'بدون اسم' }} - {{ $asset->id_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ===== من ===== -->
            <div class="form-group">
                <label> من: <i class="fa-solid fa-arrow-right-from-bracket"></i></label>
                <div class="filter-buttons">
                    <span   class="filter-btn-from tab "  data-type="employees">الموظفين</span>
                    <span   class="filter-btn-from tab" data-type="customers">العملاء</span>
                    <span   class="filter-btn-from tab" data-type="suppliers">الموردين</span>
                </div>

                <input type="text" id="fromInput" placeholder="ابحث عن العنصر">
                <input type="hidden" name="from_no" id="fromNo">
                <input type="hidden" name="from_no_type" id="fromNoType">

                <select id="fromSelect" size="5" class="asset-search">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" data-group="employees"
                                data-dep="{{$emp->item->department_id}}"

                        >
                            {{ $emp->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                    @foreach($customers as $cus)
                        <option value="{{ $cus->id }}" data-group="customers"
                                data-dep="{{$cus->item->department_id}}"
                        >
                            {{ $cus->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" data-group="suppliers"
                                data-dep="{{$sup->item->department_id}}"
                        >
                            {{ $sup->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ===== إلى ===== -->
            <div class="form-group">
                <label>إلى: <i class="fa-solid fa-arrow-right-to-bracket"></i></label>
                <div class="filter-buttons">
                    <span   class="filter-btn-to tab" data-type="employees">الموظفين</span>
                    <span   class="filter-btn-to tab" data-type="customers">العملاء</span>
                    <span   class="filter-btn-to tab" data-type="suppliers">الموردين</span>
                </div>

                <input type="text" id="toInput" placeholder="ابحث عن العنصر">
                <input type="hidden" name="to_no" id="toNo" required>
                <input type="hidden" name="to_no_type" id="toNoType" required>

                <select id="toSelect" size="5" class="asset-search" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}"
                                data-dep="{{$emp->item->department_id}}"
                                data-group="employees">
                            {{ $emp->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                    @foreach($customers as $cus)
                        <option value="{{ $cus->id }}" data-group="customers"
                                data-dep="{{$cus->item->department_id}}"
                        >
                            {{ $cus->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" data-group="suppliers"
                                data-dep="{{$sup->item->department_id}}"
                        >
                            {{ $sup->item->name ?? 'بدون اسم' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ===== بقية النموذج ===== -->
            <div class="row-2">
                <div class="form-group">
                    <label>تاريخ ووقت النقل <i class="fa-solid fa-calendar-days"></i></label>
                    <input type="datetime-local" name="movement_datetime" value="{{ old('movement_datetime') }}" required>
                </div>
                <div class="form-group">
                    <label>وجهة النقل<i class="fa-solid fa-location-dot"></i></label>
                    <input type="text" name="movement_destination" value="{{ old('movement_destination') }}">
                </div>
            </div>

            <div class="form-group">
                <label>السبب<i class="fa-solid fa-circle-exclamation"></i></label>
                <textarea name="reason" rows="3">{{ old('reason') }}</textarea>
            </div>

            <div class="form-group">
                <label>حالة الأصل <i class="fa-solid fa-sitemap"></i></label>
                <input type="text" name="asset_status">
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <a href="{{ route('asset_movements.index') }}" class="btn-cancel">إلغاء</a>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/assetMovements.js') }}"></script>
</x-app-layout>
