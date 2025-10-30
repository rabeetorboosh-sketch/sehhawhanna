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
    <h1> تقرير     طلبات العملاء حسب العملية  </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>


{{-- لكل حركة، نعرض المعاملات الخاصة بها --}}
@foreach($requests as $request)
    <div class="dtl-group">
        @if($request->employee)
            <div class="dtl">
                <span style="color:#777;">الموظف: {{ $request->employee?->item?->name ?? $request->employee?->name }}</span>
            </div>
        @endif
        @if($request->customer)
            <div class="dtl">
                <span>العميل: {{ $request->customer?->item?->name ?? '-' }}</span>
            </div>
        @endif
        @if($request->salesRout)
            <div class="dtl">
                <span>الطريق: {{ $request->salesRout?->name ?? '-' }}</span>
            </div>
        @endif
        <div class="dtl">
            المستخدم: {{ $request->user?->name ?? '-' }}
        </div>
        <div class="dtl">
            <strong>التاريخ: {{ $request->created_at->format('Y-m-d H:i') }}</strong>
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الصنف</th>
                    <th>الكود</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>الوصف</th>

                </tr>
                </thead>
                <tbody>
                @foreach($request->items as $item)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $item->product?->item?->name ?? '-' }}</td>
                        <td>{{ $item->product?->code ?? '-' }}</td>
                        <td>{{ $item->unit?->unit?->name ?? '-' }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $request->description ?? '-' }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach



</body>
</html>
