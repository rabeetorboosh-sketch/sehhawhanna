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
    <h1> تقرير  طلبات العملاء حسب الموظف - اجمالي    </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>


@foreach($employees as $employee)
    @php
        $employeeRequests = $requests->filter(function($r) use ($employee) {
            return $r->employee_id == $employee->id;
        });
    @endphp

    @if($employeeRequests->isNotEmpty())
        <div class="employee-title">
            {{ $employee->item?->name ?? 'موظف غير معروف' }}
        </div>

        @php
            $groupedItems = collect();

            foreach ($employeeRequests as $request) {
                foreach ($request->items as $item) {
                    $productName = $item->product?->item?->name ?? 'بدون اسم صنف';
                    $unitName = $item->unit?->unit?->name ?? '-';
                    $key = $productName . '_' . $unitName;

                    $existing = $groupedItems->get($key, [
                        'product' => $productName,
                        'unit' => $unitName,
                        'count' => 0,
                    ]);

                    $existing['count'] += $item->count;

                    $groupedItems->put($key, $existing);
                }
            }
        @endphp

        @if($groupedItems->isNotEmpty())
            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="table sortable">
                        <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الوحدة</th>
                            <th>إجمالي الكمية المطلوبة</th>
                            <th>عدد الطلبات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groupedItems as $row)
                            @php
                                $requestCount = $employeeRequests->filter(function($r) use ($row) {
                                    return $r->items->contains(function($i) use ($row) {
                                        return ($i->product?->item?->name ?? '') === $row['product'];
                                    });
                                })->count();
                            @endphp
                            <tr>
                                <td>{{ $row['product'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td>{{ $row['count'] }}</td>
                                <td>{{ $requestCount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2">إجمالي الموظف</td>
                            <td>{{ $groupedItems->sum('count') }}</td>
                            <td>{{ $employeeRequests->count() }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    @endif
@endforeach
</body>
</html>

