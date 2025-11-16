<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل حركة المستودع
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/transactionShow.css') }}">

    <div class="transaction-container">


        <!-- بطاقة تفاصيل الحركة -->
        <div class="transaction-card">
            <h3>معلومات الحركة</h3>
            <div class="transaction-info">
                <p><strong>رقم الحركة:</strong> {{ $transaction->id }}</p>
                <p><strong>المستخدم:</strong> {{ $transaction->user?->name ?? '-' }}</p>
                <p><strong>الموظف:</strong> {{ $transaction->employee?->item?->name ?? '-' }}</p>
                <p><strong>من مستودع:</strong> {{ $transaction->fromStore?->name ?? '-' }}</p>
                <p><strong>إلى مستودع:</strong> {{ $transaction->toStore?->name ?? '-' }}</p>
                <p><strong>نوع الحركة:</strong> {{ $transaction->movement?->name ?? '-' }}</p>
                <p><strong>الوصف:</strong> {{ $transaction->description ?? '-' }}</p>
                <p><strong>الحالة:</strong> {{ $transaction->status }}</p>
                <p><strong>تاريخ الإنشاء:</strong> {{ $transaction->created_at }}</p>

                <p><strong>الموقع   :</strong> {{ $transaction->empSignature?->item?->name??'لم يتم التوقيع من الموظف' }}</p>
                <div>

                    @if(isset($transaction->media) && $transaction->media->isNotEmpty())
                        <h3 class="section-title">الصور</h3>
                        <div class="media-grid">

                            @foreach($transaction->media as $media)
                                <a href="{{ asset($media->url) }}" target="_blank">
                                    <img src="{{ asset($media->url) }}" alt="صورة التقرير" class="media-thumb">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- بطاقة الأصناف -->
        <div class="transaction-card">
            <h3>الأصناف</h3>
            <div class="items-list">
                <p class="items-summary">
                    <strong>إجمالي الكمية:</strong> {{ $transaction->items->sum('count') }}
                </p>
                <table class="items-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transaction->items as $index => $item)
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
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

    </div>
</x-app-layout>
