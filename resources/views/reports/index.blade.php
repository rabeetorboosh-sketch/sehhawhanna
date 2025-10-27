<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            البلاغات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('reports.create',$department??'') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>نوع البلاغ</th>
                        <th>القسم</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->user?->name }}</td>
                            <td>{{ $report->reportType?->name }}</td>
                            <td>{{ $report->department?->name }}</td>
                            <td>{{ $report->status == 1 ? 'قيد الانتضار' : 'معلق' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('reports.edit', [$report->id,$department]) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $report->id }}" action="{{ route('reports.destroy',[$report->id,$department]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $report->id }})">
                                            حذف
                                        </button>
                                    </form>
                                    <a href="{{ route('reports.show', $report->id) }}" class="btn btn-primary">عرض</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
