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

        .num strong {
            border: 1px dashed;
            margin: 0 5px 5px -3px;
            padding: 5px;
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
            padding: 3px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        window.print();
    });

    window.onafterprint = function() {
        window.history.back();
    };

    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
</script>

<header>
    {{-- <img src="{{ asset('images/company_logo.png') }}" alt="Logo"> --}}
    <h1>تقرير التقييمات حسب العملية-تحليلي</h1>
    <h2>الوقت: {{ now()->format('H:i d/m/Y') }}</h2>
    <h2>التاريخ من: {{ $request->from_date ?? '---' }} إلى: {{ $request->to_date ?? '---' }}</h2>
</header>

@foreach($ratings as $rating)
    <div class="dtl-group">
        <div class="dtl">
            <strong>الموظف:</strong> {{ $rating->item?->name ?? '-' }}
        </div>
        <div class="dtl">
            <strong>المستخدم:</strong> {{ $rating->user?->name ?? '-' }}
        </div>
        <div class="dtl">
            <strong>الشهر:</strong> {{ $rating->date ?? '-' }}
        </div>

    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>الوحدة</th>
                <th>التقييم (%)</th>
                <th>الوزن</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalWeighted = 0;
                $totalMultiply = 0;
            @endphp
            @foreach($rating->items as $index => $item)
                @php
                    $weighted = $item->percentage * $item->ratingUnit->multiply;
                    $totalWeighted += $weighted;
                    $totalMultiply += $item->ratingUnit->multiply;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->ratingUnit->name ?? '-' }}</td>
                    <td>{{ $item->percentage }}%</td>
                    <td>{{ $item->ratingUnit->multiply }}</td>
                </tr>
            @endforeach
            </tbody>
            @php
                $finalPercentage = $totalMultiply > 0 ? $totalWeighted / $totalMultiply : 0;
            @endphp
            <tfoot class="bg-gray-100">
            <tr>
                <th colspan="3" style="text-align:right">التقييم النهائي :</th>
                <th>{{ number_format($finalPercentage, 2) }}%</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endforeach

</body>
</html>
