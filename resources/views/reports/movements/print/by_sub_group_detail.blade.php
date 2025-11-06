
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
        .store-title {
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
        .dtl-group {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 2px 0;
        }
        .dtl {
            background: #ffffff;
            box-shadow: 0 0 5px -2px gray;
            padding: 3px;border-radius: 5px;
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

    <h1> تقرير  حركات المخزون حسب المجموعة -تحليلي </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>

@php
    $groupedTransactions = [];
    foreach ($transactions as $transaction) {
        foreach ($transaction->items as $item) {
            $subGroup = $item->product?->item?->subGroup?->name ?? 'غير محدد';
            $groupedTransactions[$subGroup][] = [
                'transaction' => $transaction,
                'item' => $item,
            ];
        }
    }
@endphp

@foreach($groupedTransactions as $subGroupName => $records)

    <div class="subgroup-title table-title">
        {{ $subGroupName }}( {{ $records[0]['item']->product?->item?->mainGroup?->name ?? '-' }} )
    </div>
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>رقم العملية</th>
                    <th>نوع العملية</th>
                    <th>من المستودع</th>
                    <th>إلى المستودع</th>
                    <th>الصنف</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>الموظف</th>
                    <th>المستخدم</th>
                    <th>التاريخ</th>
                </tr>
                </thead>
                <tbody>
                @php $totalCount = 0; @endphp
                @foreach($records as $record)
                    @php
                        $transaction = $record['transaction'];
                        $item = $record['item'];
                        $totalCount += $item->count;
                    @endphp
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->Movement?->name ?? '-' }}</td>
                        <td>{{ $transaction->FromStore?->name ?? '-' }}</td>
                        <td>{{ $transaction->ToStore?->name ?? '-' }}</td>
                        <td>{{ $item->product?->item?->name ?? '-' }}</td>
                        <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $transaction->employee?->item?->name ?? '-' }}</td>
                        <td>{{ $transaction->user?->name ?? '-' }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="6" style="text-align:right;">إجمالي الكمية للمجموعة:</td>
                    <td>{{ $totalCount }}</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{asset('js/table.js')}}"></script>


@endforeach
</html>
