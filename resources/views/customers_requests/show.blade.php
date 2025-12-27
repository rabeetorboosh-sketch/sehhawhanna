<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل طلب العميل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/assetShow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- القسم الأول: بيانات الطلب الأساسية --}}
                <h3 class="section-title">بيانات الطلب</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">رقم الطلب</span>
                        <span class="info-content">#{{ $request->id }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">العميل</span>
                        <span class="info-content">{{ $request->customer?->item?->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الموظف (المندوب)</span>
                        <span class="info-content">{{ $request->employee?->item->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">خط السير</span>
                        <span class="info-content">{{ $request->salesRout?->name ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @switch($request->status)
                                @case('approved') <span class="status completed">معتمد</span> @break
                                @case('pending') <span class="status pending">قيد الانتظار</span> @break
                                @default <span class="status transferred">{{ $request->status }}</span>
                            @endswitch
                        </span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">تاريخ الطلب</span>
                        <span class="info-content">{{ $request->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($request->description)
                        <div class="info-card" style="grid-column: span 2;">
                            <span class="info-title">الوصف/الملاحظات</span>
                            <span class="info-content">{{ $request->description }}</span>
                        </div>
                    @endif
                </div>

                {{-- القسم الثاني: العناصر المطلوبة --}}
                <h3 class="section-title">العناصر المطلوبة</h3>

                {{-- عرض الجدول على أجهزة الكمبيوتر --}}
                <div class="table-wrap desktop-view">
                    <div class="table-scroll">
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th>الوحدة</th>
                                <th>الكمية</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($request->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product?->item?->name ?? '—' }}</td>
                                    <td>{{ $item->unit?->unit?->name ?? '—' }}</td>
                                    <td>{{ $item->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr style="background: #f8fafc; font-weight: bold;">
                                <td colspan="3" style="text-align: left; padding-left: 20px;">إجمالي الكمية:</td>
                                <td>{{ $request->items->sum('count') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- عرض كبطاقات على الهواتف --}}
                <div class="mobile-view">
                    @foreach($request->items as $index => $item)
                        <div class="card">
                            <p><strong>المنتج:</strong> {{ $item->product?->item?->name ?? '—' }}</p>
                            <p><strong>الوحدة:</strong> {{ $item->unit?->unit?->name ?? '—' }}</p>
                            <p><strong>الكمية:</strong> {{ $item->count }}</p>
                        </div>
                    @endforeach
                    <div class="card" style="background: #edf2f7; border-right: 4px solid #4a5568;">
                        <p><strong>إجمالي الكميات:</strong> {{ $request->items->sum('count') }}</p>
                    </div>
                </div>

                {{-- أزرار التحكم --}}
                <div class="mt-8">
                    <a href="{{ route('customersRequests.index') }}" class="btn-back" style="display: inline-flex; align-items: center; gap: 8px; background: #6b7280; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">
                        <i class="fa-solid fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
