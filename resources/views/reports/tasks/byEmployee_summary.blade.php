@extends('reports.tasks.filters')
@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>عدد المهام</th>
                    <th>عدد المنجزة</th>
                    <th>التي تم تحويلها للادارة</th>
                    <th>التي تم تجاوز وقتها</th>
                    <th>اجمالي التقييم</th>
                    <th> نسبة الانجاز</th>
                    <th>تفاصيل</th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    @php
                        $stats = $summary[$employee->id] ?? [
                            'total' => 0,
                            'completed' => 0,
                            'forwarded' => 0,
                            'overdue' => 0,
                            'score' => 0,
                            'completion_percentage' => 0,
                        ];

                        // اسم الموظف: حاولنا ضبط fallback لو الحقل مختلف
                        $employeeName = $employee->item?->name ?? $employee->name ?? $employee->full_name ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employeeName }}</td>
                        <td>{{ $stats['total'] }}</td>
                        <td>{{ $stats['completed'] }}</td>
                        <td>{{ $stats['forwarded'] }}</td>
                        <td>{{ $stats['overdue'] }}</td>
                        <td>{{ number_format($stats['score'], 2) }}</td>
                        <td>
                            <div style="margin-top:6px; width:120px; display: inline-block">
                                <div style="background:#eee;border-radius:6px;height:8px;overflow:hidden;">
                                    <div style="width:{{ $stats['completion_percentage'] }}%;height:8px;border-radius:6px;background:linear-gradient(90deg,#3b82f6,#06b6d4);"></div>
                                </div>
                                <small>{{ $stats['completion_percentage'] }}%</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('reportTasks.byEmployeeDetail', $employee->id )}}" class="btn btn-sm">عرض تحليلي </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary" style="margin-top:18px; display:flex; gap:18px; flex-wrap:wrap;">
        <div class="num">
            عدد المهام: <strong>{{ $totals['totalTasks'] ?? 0 }}</strong>
        </div>
        <div class="num">
            عدد المستلمة: <strong>{{ $totals['totalReceived'] ?? 0 }}</strong>
        </div>
        <div class="num">
            عدد المحولة للادارة: <strong>{{ $totals['totalForwarded'] ?? 0 }}</strong>
        </div>
        <div class="num">
            عدد التي تم تجاوز وقتها: <strong>{{ $totals['totalOverdue'] ?? 0 }}</strong>
        </div>
    </div>
@endsection
