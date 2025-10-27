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
    <h1> تقرير  الرقابة اليومية </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>
<div class="table-wrap">
    <div class="table-scroll">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>الأصل</th>
                <th>من </th>
                <th>إلى </th>
                <th>تاريخ ووقت النقل</th>
                <th>السبب</th>
                <th>حالة الأصل</th>
                <th>وجهة النقل</th>
                <th>المستخدم</th>

            </tr>
            </thead>
            <tbody>
            @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->id }}</td>
                    <td>{{ $movement->asset->item->name }}</td>
                    <td>
                        @if($movement->from_item_type==4)
                            {{ $movement->fromEmployee?->item?->name }}
                        @elseif($movement->from_item_type==8)
                            {{ $movement->fromCustomer?->item?->name }}
                        @elseif($movement->from_item_type==9)
                            {{ $movement->fromSupplier?->item?->name }}
                        @endif
                    </td>
                    <td>
                        @if($movement->to_item_type==4)
                            {{ $movement->toEmployee?->item?->name }}
                        @elseif($movement->to_item_type==8)
                            {{ $movement->toCustomer?->item?->name }}
                        @elseif($movement->to_item_type==9)
                            {{ $movement->toSupplier?->item?->name }}
                        @endif
                    </td>

                    <td>{{ $movement->movement_datetime }}</td>
                    <td>{{ $movement->reason }}</td>
                    <td>
                        {{ $movement->asset_status?? ''}}
                    </td>
                    <td>{{ $movement->movement_destination }}</td>
                    <td>{{ $movement->user->name ?? 'غير محدد' }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>


    </div>
</div>
</body>
</html>
