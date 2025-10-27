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
@php
    $proplemsCount=0;
    $assiments=0;
    $receipt=0;
@endphp
<div class="table-wrap">
    <div class="table-scroll">
        <table class="table sortable">
            <thead>
            <tr>
                <th>#</th>
                <th>القسم </th>
                <th> البند </th>
                <th>وحدة الرقابة </th>
                <th>التاريخ  </th>
                <th>المشكلة </th>
                <th>المتسبب </th>
                <th>الحالة </th>
                <th>مسندة </th>
                <th>تم حلها  </th>

            </tr>
            </thead>
            <tbody>

            @foreach($dailyControls as $dailyControl)

                @foreach($dailyControl->items as $CItem)
                    <tr>
                        <td>{{ $CItem->id }}</td>
                        <td>{{ $CItem->controlUnit?->section?->name }}</td>
                        <td>{{ $CItem->item?->name ?? 'بدون تحديد' }}</td>
                        <td>{{ $CItem->controlUnit?->name }}</td>
                        <td>{{ $dailyControl->created_at }}</td>
                        <td>{{ $CItem->description??'ليس فيها مشكلة' }}</td>
                        <td>{{ $CItem->causer?->item?->name }}</td>
                        <td>
                            @if( $CItem->is_correct ==1)
                                ليس فيها مشكلة
                            @else
                                @php(++$proplemsCount)
                                فيها مشكلة
                            @endif
                        </td>
                        @if(isset($CItem->assignments) and $CItem->assignments->isNotEmpty())
                            @php($assiments++)
                            <td> نعم</td>
                            @foreach($CItem->assignments as $assignment)
                                @if( isset($assignment->receipt) and $assignment->receipt->isNotEmpty() )
                                    @php($receipt++)

                                    <td>نعم</td>
                                    @break
                                @else
                                    <td>لا</td>
                                    @break
                                @endif
                            @endforeach
                        @else
                            <td>لا</td>
                            <td>لا</td>
                        @endif



                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="summary">
    <div class="num">
        عدد المشاكل:<strong>{{$proplemsCount}}</strong>
    </div>
    <div class="num">
        عدد الموجهة:<strong>{{$assiments}}</strong>
    </div>
    <div class="num">
        عدد المحلولة:<strong>{{$receipt}}</strong>
    </div>
</div>


</body>
</html>
