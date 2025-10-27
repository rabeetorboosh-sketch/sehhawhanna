
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
    {{--    <img src="{{ asset('images/company_logo.png') }}" alt="Logo">--}}
    <h1> تقرير  حركات المخزون حسب المخزن-اجمالي </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>


@foreach($stores as $store)
    @if(isset($summary[$store->name]))
        <div class="store-title">
            {{ $store->name }}
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>الصنف</th>
                        <th>الوحدة</th>
                        <th>إجمالي داخل</th>
                        <th>إجمالي خارج</th>
                        <th>الرصيد</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $totalIn = 0;
                        $totalOut = 0;
                    @endphp

                    @foreach($summary[$store->name] as $productName => $units)
                        @foreach($units as $unitName => $data)
                            @php
                                $balance = $data['in'] - $data['out'];
                                $totalIn += $data['in'];
                                $totalOut += $data['out'];
                            @endphp
                            <tr>
                                <td>{{ $productName }}</td>
                                <td>{{ $unitName }}</td>
                                <td>{{ $data['in'] }}</td>
                                <td>{{ $data['out'] }}</td>
                                <td>{{ $balance }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">الإجمالي العام:</td>
                        <td>{{ $totalIn }}</td>
                        <td>{{ $totalOut }}</td>
                        <td>{{ $totalIn - $totalOut }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endforeach
</body>
</html>
