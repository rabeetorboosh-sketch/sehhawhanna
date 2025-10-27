<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            تفاصيل المسار البيعي
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/transactionShow.css') }}">

    <div class="transaction-container">

        <!-- بطاقة تفاصيل المسار -->
        <div class="transaction-card">
            <h3>معلومات المسار</h3>
            <div class="transaction-info">
                <p><strong>رقم المسار:</strong> {{ $salesRout->id }}</p>
                <p><strong>اسم المسار:</strong> {{ $salesRout->name }}</p>
                <p><strong>الموظف المسؤول:</strong> {{ $salesRout->employee?->item?->name ?? '-' }}</p>
                <p><strong>تاريخ الإنشاء:</strong> {{ $salesRout->created_at?->format('Y-m-d') ?? '-' }}</p>
            </div>
        </div>

        <!-- بطاقة العملاء المرتبطين (مستقبلية) -->
        <div class="transaction-card">
            <h3>العملاء المرتبطين بهذا المسار</h3>
            <p class="text-sm text-gray-500 mb-2">* هذه البيانات تجريبية وستُستبدل لاحقًا ببيانات حقيقية من قاعدة البيانات.</p>

            <table class="items-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم العميل</th>
                    <th>رقم الهاتف</th>
                </tr>
                </thead>
                <tbody>


                @foreach($salesRout->customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->item?->name}}</td>
                        <td>{{ $customer->phone??'-'}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- زر العودة -->
        <div class="actions">
            <a href="{{ route('sales_routs.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-right"></i> عودة للقائمة
            </a>
        </div>

    </div>
</x-app-layout>
