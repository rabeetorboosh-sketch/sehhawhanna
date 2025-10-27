@extends('reports.monitorings.filters')
@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>المستخدم</th>
                    <th>اليوم</th>
                    <th>عدد الوحدات</th>
                    <th>عدد البنود</th>
                    <th>الحالة</th>

                </tr>
                </thead>
                <tbody>
                @foreach($dailyControls as $control)

                    @php
                        $totalUnits = $control->items->pluck('control_unit_id')->unique()->count();
                        $totalItems = $control->items->count();
                    @endphp
                    <tr>
                        <td>{{ $control->id }}</td>
                        <td>{{ $control->user?->name }}</td>
                        <td>{{ $control->day }}</td>
                        <td>{{ $totalUnits }}</td>
                        <td>{{ $totalItems }}</td>
                        <td>
                            @if($control->items->every(fn($item) => $item->is_correct))
                                <span class="text-green-600">سليم</span>
                            @elseif($control->items->every(fn($item) => !$item->is_correct))
                                <span class="text-red-600">مشكلة</span>
                            @else
                                <span class="text-yellow-600">مختلط</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
