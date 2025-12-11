<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض عملية التسكين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- ======================= بيانات التسكين ======================= --}}
                <h3 class="section-title">بيانات عملية التسكين</h3>

                <div class="info-grid">

                    <div class="info-card">
                        <span class="info-title">الوحدة السكنية</span>
                        <span class="info-content">
                            {{ $assignment->unit?->name }} - {{ $assignment->unit?->unit_code }}
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">تاريخ التسكين</span>
                        <span class="info-content">
                            {{ $assignment->assignment_date }}
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">المسجل</span>
                        <span class="info-content">
                            {{ $assignment->user?->name ?? '-' }}
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الملاحظات</span>
                        <span class="info-content">
                            {{ $assignment->notes ?? '—' }}
                        </span>
                    </div>

                </div>

                {{-- ======================= الموظفون ======================= --}}
                <h3 class="section-title">الموظفون المسكنون</h3>

                {{-- نسخة الكمبيوتر --}}
                <div class="table-wrap desktop-view">
                    <div class="table-scroll">
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>الموظف</th>
                                <th>الغرفة</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignment->items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->employee?->item?->name ?? '-' }}</td>
                                    <td>{{ $item->room?->room_name ?? '-' }}</td>
                                    <td>{{ $item->start_date ?? '-' }}</td>
                                    <td>{{ $item->end_date ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



                <div class="mt-6">
                    <a href="{{ route('housing_assignments.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
