<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض الوحدة السكنية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات الوحدة --}}
                <h3 class="section-title">بيانات الوحدة السكنية</h3>
                <div class="info-grid">

                    <div class="info-card">
                        <span class="info-title">رقم الوحدة</span>
                        <span class="info-content">{{ $unit->id }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">كود الوحدة</span>
                        <span class="info-content">{{ $unit->unit_code }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">اسم الوحدة</span>
                        <span class="info-content">{{ $unit->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">نوع الوحدة</span>
                        <span class="info-content">{{ $unit->unit_type ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">عدد المطابخ</span>
                        <span class="info-content">{{ $unit->total_kitchens }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">عدد الحمامات</span>
                        <span class="info-content">{{ $unit->total_bathrooms }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">العنوان</span>
                        <span class="info-content">{{ $unit->address ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @if($unit->status == 'available')
                                متاحة
                            @else
                                مشغولة
                            @endif
                        </span>
                    </div>

                    <div class="info-card full">
                        <span class="info-title">ملاحظات</span>
                        <span class="info-content">{{ $unit->notes ?? 'لا يوجد' }}</span>
                    </div>

                </div>

                {{-- جدول الغرف --}}
                <h3 class="section-title">الغرف التابعة لهذه الوحدة</h3>

                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-center border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">اسم الغرفة</th>
                            <th class="py-3 px-4 border">عدد الأسرة</th>
                            <th class="py-3 px-4 border">المسكن</th>
                            <th class="py-3 px-4 border">الفارغ</th>
                            <th class="py-3 px-4 border">نوع الغرفة</th>
                            <th class="py-3 px-4 border">حمام خاص؟</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($unit->rooms as $index => $room)

                            @php
                                // عدد الموظفين المسكنين في الغرفة (الذين لا يملكون end_date)
                                $assigned = \App\Models\HousingAssignmentItem::where('housing_unit_room_id', $room->id)
                                            ->whereNull('end_date')
                                            ->count();

                                $empty = $room->bed_count - $assigned;
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border">{{ $room->room_name }}</td>

                                <td class="py-2 px-4 border">{{ $room->bed_count }}</td>

                                <td class="py-2 px-4 border text-green-600 font-bold">
                                    {{ $assigned }}
                                </td>

                                <td class="py-2 px-4 border text-blue-600 font-bold">
                                    {{ $empty }}
                                </td>

                                <td class="py-2 px-4 border">{{ $room->room_type ?? '-' }}</td>
                                <td class="py-2 px-4 border">{{ $room->has_bathroom ? 'نعم' : 'لا' }}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- عمليات التسكين --}}
                <h3 class="section-title mt-8">عمليات التسكين لهذه الوحدة</h3>

                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-center border border-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">المسكن بواسطة</th>
                            <th class="py-3 px-4 border">عدد الموظفين</th>
                            <th class="py-3 px-4 border">تاريخ التسكين</th>
                            <th class="py-3 px-4 border">ملاحظات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($unit->assignments as $index => $assign)
                            <tr>
                                <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border">{{ $assign->creator->name ?? '---' }}</td>
                                <td class="py-2 px-4 border">{{ $assign->items->count() }}</td>
                                <td class="py-2 px-4 border">{{ $assign->assignment_date }}</td>
                                <td class="py-2 px-4 border">{{ $assign->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 px-4 border text-gray-500">
                                    لا توجد عمليات تسكين مسجلة لهذه الوحدة
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <a href="{{ route('housing_units.index') }}" class="btn btn-primary">عودة</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
