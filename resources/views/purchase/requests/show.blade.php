<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل طلب الشراء
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/transactionShow.css') }}">

    <div class="transaction-container">

        <!-- بطاقة معلومات طلب الشراء -->
        <div class="transaction-card">
            <h3>معلومات الطلب</h3>
            <div class="transaction-info">
                <p><strong>رقم الطلب:</strong> {{ $purchaseRequest->id }}</p>
                <p><strong>التاريخ:</strong> {{ $purchaseRequest->created_at->format('d-m-Y h:i A') }}</p>
                <p><strong>ملاحظات:</strong> {{ $purchaseRequest->note ?? '-' }}</p>
            </div>
        </div>

        <!-- بطاقة الأصناف -->
        <div class="transaction-card">
            <h3>العناصر المطلوبة</h3>

            <div class="items-list">
                <p class="items-summary">
                    <strong>إجمالي الكمية:</strong>
                    {{ $purchaseRequest->requestItems->sum('pur_request_count') }}
                </p>

                <table class="items-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>الحالة</th>
                        <th>إجراء</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchaseRequest->requestItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name ?? 'غير متوفر' }}</td>
                            <td>{{ $item->unit?->name ?? 'غير متوفر' }}</td>
                            <td>{{ $item->pur_request_count }}</td>
                            <td>
                                @if($item->is_confirmed)
                                    <span style="color: green; font-weight: bold;">معتمد</span>
                                @else
                                    <span style="color: red; font-weight: bold;">غير معتمد</span>
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->isAdmin())
                                    <form method="POST"
                                          action="{{ route('purchase_requests.confirmItem', $item->id) }}">
                                        @csrf
                                        <button class="btn-small {{ $item->is_confirmed ? 'btn btn-danger' : 'btn btn-primary' }}">
                                            {{ $item->is_confirmed ? 'إلغاء الاعتماد' : 'اعتماد' }}
                                        </button>
                                    </form>
                                @else
                                    -
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
            <a href="{{ route('purchase_requests.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
            </a>

            @if(Auth::user()->permissions('pur-operations-request')?->can_edit == 1)
                <a href="{{ route('purchase_requests.edit', $purchaseRequest) }}" class="btn btn-worn">
                    <i class="fa-solid fa-pen"></i> تعديل
                </a>
            @endif
        </div>

    </div>
</x-app-layout>
