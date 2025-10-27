@extends('reports.supervises.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

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
                👤 {{ $user?->name ?? 'غير معروف' }}
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
@endsection
