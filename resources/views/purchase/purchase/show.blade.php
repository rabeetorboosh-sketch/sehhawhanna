<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل فاتورة الشراء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/transactionShow.css') }}">

    <div class="transaction-container">

        <!-- بطاقة معلومات الفاتورة -->
        <div class="transaction-card">
            <h3>معلومات الفاتورة</h3>
            <div class="transaction-info">
                <p><strong>رقم الفاتورة:</strong> {{ $purchase->id }}</p>
                <p><strong>التاريخ:</strong> {{ $purchase->created_at->format('d-m-Y h:i A') }}</p>
                <p><strong>ملاحظات:</strong> {{ $purchase->note ?? '-' }}</p>
            </div>
        </div>

        <!-- بطاقة العناصر المشتراة -->
        <div class="transaction-card">
            <h3>العناصر المشتراة</h3>

            <div class="items-list">
                <p class="items-summary">
                    <strong>إجمالي الكمية:</strong>
                    {{ $purchase->purchaseItems->sum('purchase_count') }}
                </p>

                <table class="items-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>تم التأكيد؟</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchase->purchaseItems as $index => $item)

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->item->name ?? 'غير متوفر' }}</td>
                            <td> {{  $item->unit->unit->name ?? 'غير متوفر' }}</td>
                            <td>{{ $item->pur_purchase_count }}</td>
                            <td>
                                @if($item->is_confirmed)
                                    <span style="color: green; font-weight: bold;">✅</span>
                                @else
                                    <span style="color: red; font-weight: bold;">❌</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- الأزرار -->
        <div class="actions">
            <a href="{{ route('purchase_purchase.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
            </a>

            @if(Auth::user()->isAdmin() || Auth::user()->is_purchase() > 1)
                <a href="{{ route('purchase_purchase.edit', $purchase) }}" class="btn btn-worn">
                    <i class="fa-solid fa-pen"></i> تعديل
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
