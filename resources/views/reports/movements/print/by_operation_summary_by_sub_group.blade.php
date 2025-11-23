
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
        .summary-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: #444;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #555;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .total-box {
            background: #f7f7f7;
            border: 1px solid #ddd;
            padding: 8px 15px;
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
        }
        .divider {
            margin: 40px 0;
            border-top: 2px dashed #999;
        }
        th, td {
            white-space: nowrap;
        }

        /* --- تنسيق صناديق الأصناف داخل المجموعات الفرعية --- */
        .product-box {
            display: inline-block;
            padding: 6px 12px;
            background: #f7f7f7;
            margin: 4px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 0.9rem;
            white-space: nowrap;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }
        .product-name {
            font-weight: bold;
            color: #333;
            display: block;
        }
        .product-count {
            color: #0055aa;
            font-weight: bold;
            margin-right: 4px;
            display: block;
            text-align: center;
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
    <h1>  ملخص العمليات حسب المجموعات الفرعية </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>




<div class="table-wrap">
    <div class="table-scroll">
        <table class="table sortable">
            <thead>
            <tr>
                <th style="width: 220px;">المجموعة الفرعية</th>
                <th>الأصناف التابعة للمجموعة</th>
                <th style="width: 160px;">إجمالي المجموعة</th>
            </tr>
            </thead>

            <tbody>
            @php $grandTotalGroups = 0; @endphp

            @foreach($summaryBySubGroup as $group)
                <tr>
                    <td>{{ $group['sub_group_name'] }}</td>

                    <td>
                        @foreach($group['items'] as $item)
                            <div class="product-box">
                                <span class="product-name">{{ $item['product_name'] }}</span>
                                <span class="product-count">{{ $item['total_count'] }}</span>
                            </div>
                        @endforeach
                    </td>

                    <td><strong>{{ $group['sub_total'] }}</strong></td>

                    @php $grandTotalGroups += $group['sub_total']; @endphp
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
