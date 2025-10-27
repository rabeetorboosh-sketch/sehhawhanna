@extends('reports.employeesRating.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <style>
        .operation-name {
            font-size: 1.4rem;
            font-weight: bold;
            color: #525255;
            margin-top: 25px;
            margin-bottom: 10px;
            padding: 8px 15px;
            border-right: 2px solid #535a5c;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
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
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .table-wrap {
            margin-bottom: 30px;
        }
    </style>

    @foreach($ratings as $rating)
        <div class="operation-name">
            {{ $rating->item->name ?? 'عنصر غير محدد' }}
        </div>

        <div class="dtl-group">
            <div class="dtl">المستخدم: {{ $rating->user->name ?? '-' }}</div>
            <div class="dtl">التاريخ: {{ $rating->date }}</div>
            <div class="dtl">عدد البنود: {{ $rating->items->count() }}</div>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
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
        </div>
    @endforeach

    @if($ratings->isEmpty())
        <p style="text-align:center; color:#777;">لا توجد تقييمات مطابقة لخيارات البحث</p>
    @endif

@endsection
