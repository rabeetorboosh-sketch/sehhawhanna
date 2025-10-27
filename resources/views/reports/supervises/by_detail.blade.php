@extends('reports.supervises.filters')

@section('tbl')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    @php
        $transToManagement = 0;
        $completed = 0;
    @endphp

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="table sortable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>المشرف</th>
                    <th>المشكلة</th>
                    <th>التاريخ</th>
                    <th>تم الإنجاز؟</th>
                    <th>محولة للادارة؟</th>
                    <th>الإجراء</th>
                </tr>
                </thead>
                <tbody>
                @foreach($supervisors as $supervisor)
                    <tr>
                        <td>{{ $supervisor->id }}</td>
                        <td>{{ $supervisor->customer?->item?->name ?? '—' }}</td>
                        <td>{{ $supervisor->user->name ?? '—' }}</td>
                        <td>{{ $supervisor->issue }}</td>
                        <td>{{ $supervisor->start_time }}</td>
                        <td>
                            @if($supervisor->is_completed)
                                نعم
                                @php(++$completed)
                            @else
                                لا
                            @endif
                        </td>
                        <td>
                            @if($supervisor->transferred_to_management)
                                نعم
                                @php(++$transToManagement)
                            @else
                                لا
                            @endif
                        </td>
                        <td>

                            <a href="{{ route('supervises.show', $supervisor->id) }}" class="btn btn-worn"
                               style="background-color:#17a2b8;">
                                <i class="fa-solid fa-eye"></i> عرض
                            </a>


                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary">
        <div class="num">
            عدد التقارير:<strong>{{ $supervisors->count() }}</strong>
        </div>
        <div class="num">
            عدد المنجزة:<strong>{{ $completed }}</strong>
        </div>
        <div class="num">
            عدد المحولة للادارة:<strong>{{ $transToManagement }}</strong>
        </div>
    </div>

    <div class="pagination">
        @if ($supervisors->onFirstPage())
            <span class="disabled">السابق</span>
        @else
            <a href="{{ $supervisors->previousPageUrl() }}" rel="prev">السابق</a>
        @endif

        @foreach ($supervisors->getUrlRange(1, $supervisors->lastPage()) as $page => $url)
            @if ($page == $supervisors->currentPage())
                <a class="active">{{ $page }}</a>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($supervisors->hasMorePages())
            <a href="{{ $supervisors->nextPageUrl() }}" rel="next">التالي</a>
        @else
            <span class="disabled">التالي</span>
        @endif
    </div>


@endsection
