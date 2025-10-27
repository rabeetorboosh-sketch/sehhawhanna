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
    <h1> تقرير المشرفين     </h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date??'---' }} إلى: {{ $request->to_date?? '---' }}</h2>
</header>
@foreach($supervises as $userId => $items)
    @php
        $user = $items->first()->user ?? null;
        $completed = $items->where('is_completed', 1)->count();
        $transferred = $items->where('transferred_to_management', 1)->count();
        $total = $items->count();
        $completionRate = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    @endphp

    <div class="user-section ">
        <h3 style="background:#f1f1f1; padding:10px; border-radius:8px;" class="table-title">
            المشرف : {{ $user?->name ?? 'غير معروف' }}
        </h3>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>المشكلة</th>
                        <th>التاريخ</th>
                        <th>منجزة؟</th>
                        <th>محولة؟</th>
                        <th>الإجراء</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $index => $supervisor)
                        <tr>
                            <td>{{ $supervisor->id }}</td>
                            <td>{{ $supervisor->customer?->item?->name ?? '—' }}</td>
                            <td>{{ $supervisor->issue }}</td>
                            <td>{{ $supervisor->start_time }}</td>
                            <td>{{ $supervisor->is_completed ? 'نعم' : 'لا' }}</td>
                            <td>{{ $supervisor->transferred_to_management ? 'نعم' : 'لا' }}</td>
                            <td>
                                <a href="{{ route('supervises.show', $supervisor->id) }}" class="btn btn-worn" style="background-color:#17a2b8;">
                                    <i class="fa-solid fa-eye"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr style="margin: 30px 0;">
    </div>
@endforeach




</body>
</html>
