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


        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table sortable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>التاريخ</th>
                        <th>المستخدم</th>
                        <th>عدد البنود</th>
                        <th>التقييم (%)</th>

                    </tr>
                    </thead>
                    @foreach($ratings as $rating)
                    <tbody>
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->item?->name }}</td>
                                <td>{{  $rating->date}}</td>
                            <td>{{ $rating->user->name }}</td>
                            <td>{{ $rating->items->count() }}</td>
                            <td>
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
                                @endforeach

                                {{ $totalMultiply > 0 ?   round($totalWeighted / $totalMultiply, 1) : 0}}%
                            </td>
                        </tr>

                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>


    @if($ratings->isEmpty())
        <p style="text-align:center; color:#777;">لا توجد تقييمات مطابقة لخيارات البحث</p>
    @endif

@endsection
