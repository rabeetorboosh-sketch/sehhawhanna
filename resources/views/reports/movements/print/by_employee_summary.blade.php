<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>

        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            margin: 20px;
            font-size: 14px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header img {
            height: 80px;
            display: block;
            margin: 0 auto 5px auto;
        }
        h1, h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }
        .note {
            text-align: left;
        }
        .summary {
            margin: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            background: #fcfdff;
            padding: 5px;
            box-shadow: inset 0 0 23px -13px #d8d8d8;
        }
        .num {
            border: 1px solid #e8e4e4;
            padding: 5px;
            strong {
                border: 1px dashed;
                margin: 0 5px 5px -3px;
                padding: 5px;
            }
        }
        .employee-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #34495e;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            background-color: #f3f4f6;
            border-right: 3px solid #b8bcc1;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .store-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1e293b;
            margin-top: 15px;
            margin-bottom: 8px;
            padding: 6px 15px;
            background-color: #f8fafc;
            border-right: 3px solid #cbd5e1;
            border-radius: 6px;
        }

    </style>
</head>
<body>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        // عند تحميل الصفحة مباشرة نشغل الطباعة
        window.print();
    });

    // بعد انتهاء الطباعة أو الإلغاء، نرجع للصفحة السابقة
    window.onafterprint = function() {
        // نرجع للصفحة السابقة في التاريخ
        window.history.back();
    };

    // منع المستخدم من استخدام Ctrl+P يدوياً لإعادة التشغيل غير المقصود
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault(); // يمنع الاختصار الافتراضي
            window.print();
        }
    });
</script>
<header>
    {{--    <img src="{{ asset('images/company_logo.png') }}" alt="Logo">--}}
    <h1> تقرير  حركات المخزون حسب الموظف - اجمالي    </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>


@foreach($employees as $employee)
    @php
        $employeeStores = $employee->stores;
    @endphp

    @if($employeeStores->isNotEmpty())
        <div class="employee-title">
            {{ $employee->item?->name ?? 'موظف غير معروف' }}
        </div>

        {{-- عرض كل مستودع تابع للموظف --}}
        @php
            $employeeTotal = collect();
        @endphp

        @foreach($employeeStores as $store)
            @php
                // تصفية الحركات التي تخص هذا المستودع سواء داخلة أو خارجة
                $storeTransactions = $transactions->filter(function($t) use ($store) {
                    return $t->from_store_id == $store->id || $t->to_store_id == $store->id;
                });

                $groupedItems = collect();

                foreach ($storeTransactions as $transaction) {
                    foreach ($transaction->items as $item) {
                        $productName = $item->product?->item?->name ?? 'بدون اسم صنف';
                        $unitName = $item->unit?->unit?->name ?? '-';
                        $key = $productName . '_' . $unitName;

                        $existing = $groupedItems->get($key, [
                            'product' => $productName,
                            'unit' => $unitName,
                            'in' => 0,
                            'out' => 0,
                        ]);

                        // إذا العملية تخص هذا المستودع كـ "إلى" فهي دخول، وإذا كـ "من" فهي خروج
                        if ($transaction->to_store_id == $store->id) {
                            $existing['in'] += $item->count;
                        } elseif ($transaction->from_store_id == $store->id) {
                            $existing['out'] += $item->count;
                        }

                        $groupedItems->put($key, $existing);
                    }
                }
            @endphp

            @if($groupedItems->isNotEmpty())
                <div class="table-title">
                    {{ $store->name ?? 'بدون اسم مستودع' }}
                </div>

                <div class="table-wrap">
                    <div class="table-scroll">
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th>الصنف</th>
                                <th>الوحدة</th>
                                <th>الداخل</th>
                                <th>الخارج</th>
                                <th>الصافي</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $storeTotals = ['in'=>0, 'out'=>0];
                            @endphp

                            @foreach($groupedItems as $row)
                                @php
                                    $net = $row['in'] - $row['out'];
                                    $storeTotals['in'] += $row['in'];
                                    $storeTotals['out'] += $row['out'];
                                @endphp
                                <tr>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['unit'] }}</td>
                                    <td>{{ $row['in'] }}</td>
                                    <td>{{ $row['out'] }}</td>
                                    <td>{{ $net }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="2">إجمالي المستودع</td>
                                <td>{{ $storeTotals['in'] }}</td>
                                <td>{{ $storeTotals['out'] }}</td>
                                <td>{{ $storeTotals['in'] - $storeTotals['out'] }}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @php
                    // نجمع للإجمالي الكلي للموظف
                    foreach($groupedItems as $key => $data) {
                        $existing = $employeeTotal->get($key, [
                            'product' => $data['product'],
                            'unit' => $data['unit'],
                            'in' => 0,
                            'out' => 0,
                        ]);
                        $existing['in'] += $data['in'];
                        $existing['out'] += $data['out'];
                        $employeeTotal->put($key, $existing);
                    }
                @endphp
            @endif
        @endforeach

        {{-- إجمالي المستودعات للموظف --}}
        @if($employeeTotal->isNotEmpty())
            <div class="store-title">
                🔸 إجمالي جميع مستودعات {{ $employee->item?->name ?? 'الموظف' }}
            </div>

            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>إجمالي الداخل</th>
                            <th>إجمالي الخارج</th>
                            <th>الفارق</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employeeTotal as $row)
                            <tr>
                                <td>{{ $row['product'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td>{{ $row['in'] }}</td>
                                <td>{{ $row['out'] }}</td>
                                <td>{{ $row['in'] - $row['out'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
@endforeach
</body>
</html>

