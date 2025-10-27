<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل طلب العميل
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/transactionShow.css') }}">

    <div class="transaction-container">

        <!-- بطاقة تفاصيل الطلب -->
        <div class="transaction-card">
            <h3>معلومات الطلب</h3>
            <div class="transaction-info">
                <p><strong>رقم الطلب:</strong> {{ $request->id }}</p>
                <p><strong>المستخدم:</strong> {{ $request->user?->name ?? '-' }}</p>
                <p><strong>الموظف:</strong> {{ $request->employee?->item->name ?? '-' }}</p>
                <p><strong>العميل:</strong> {{ $request->customer?->item?->name ?? '-' }}</p>
                <p><strong>خط السير:</strong> {{ $request->salesRout?->name ?? '-' }}</p>
                <p><strong>الوصف:</strong> {{ $request->description ?? '-' }}</p>
                <p><strong>الحالة:</strong>
                    @switch($request->status)
                        @case('pending') غير معتمد @break
                        @case('approved') معتمد @break
                        @default -
                    @endswitch
                </p>
                <p><strong>تاريخ الإنشاء:</strong> {{ $request->created_at }}</p>
            </div>
        </div>

        <!-- بطاقة العناصر -->
        <div class="transaction-card">
            <h3>العناصر المطلوبة</h3>
            <div class="items-list">
                <p class="items-summary">
                    <strong>إجمالي الكمية:</strong> {{ $request->items->sum('count') }}
                </p>

                <table class="items-table">
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
                            <td>{{ $item->product?->item?->name ?? '-' }}</td>
                            <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                            <td>{{ $item->count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- زر العودة -->
        <div class="actions">
            <a href="{{ route('customersRequests.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

    </div>
</x-app-layout>
