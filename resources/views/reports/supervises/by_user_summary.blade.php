@extends('reports.supervises.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>المشرف</th>
                    <th>عدد التقارير</th>
                    <th>المنجزة</th>
                    <th>المحولة للإدارة</th>
                    <th>نسبة الإنجاز</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->user->name ?? '—' }}</td>
                        <td>{{ $row->total }}</td>
                        <td>{{ $row->completed }}</td>
                        <td>{{ $row->transferred }}</td>
                        <td>
                            <div style="width:120px; background:#f0f0f0; border-radius:5px;">
                                <div style="width:{{ $row->completion_rate }}%; background:#28a745; color:#fff; text-align:center; border-radius:5px;">
                                    {{ $row->completion_rate }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
