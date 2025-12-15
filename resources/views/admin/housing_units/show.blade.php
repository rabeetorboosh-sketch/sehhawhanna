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
                                $today = \Carbon\Carbon::today();

                                $assigned = $unit->assignments
                                    ->flatMap->items
                                    ->where('housing_unit_room_id', $room->id)
                                    ->where('start_date', '<=', $today)
                                    ->filter(function ($item) use ($today) {
                                        return is_null($item->end_date) || $item->end_date >= $today;
                                    })
                                    ->count();

                                $empty = max(0, $room->bed_count - $assigned);
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


                {{-- المسكنون حالياً --}}
                <h3 class="section-title mt-8">المسكنون حالياً في هذه الوحدة</h3>

                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-center border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">اسم الموظف</th>
                            <th class="py-3 px-4 border">الغرفة</th>
                            <th class="py-3 px-4 border">تاريخ التسكين</th>
                            <th class="py-3 px-4 border">تاريخ الخروج</th>
                            <th class="py-3 px-4 border">ملاحظات</th>
                            <th class="py-3 px-4 border">عمليات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $today = \Carbon\Carbon::today();
                            $counter = 1;
                        @endphp

                        @forelse($unit->assignments as $assignment)
                            @foreach($assignment->items
                                ->where('start_date', '<=', $today)
                                ->filter(function ($item) use ($today) {
                                    return is_null($item->end_date) || $item->end_date >= $today;
                                }) as $item)

                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border">{{ $counter++ }}</td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->employee?->item?->name ?? '-' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->room->room_name ?? '-' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->start_date }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->end_date ?? 'مستمر' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        {{ $item->notes ?? '-' }}
                                    </td>
                                    <td class="py-2 px-4 border">
                                        <a href="{{ route('housing_assignments.out', $item->id) }}"
                                           class="btn btn-danger"
                                           onclick="return confirm('هل أنت متأكد من إخراج هذا الموظف؟')">
                                            اخراج
                                        </a>
                                    </td>
                                </tr>

                            @endforeach
                        @empty
                        @endforelse

                        @if($counter === 1)
                            <tr>
                                <td colspan="6" class="py-3 px-4 border text-gray-500">
                                    لا يوجد مسكنون حالياً في هذه الوحدة
                                </td>
                            </tr>
                        @endif

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
                            <th  >عمليات</th>
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
                                <td class="py-2 px-4 border"> <a href="{{route('housing_assignments.show',$assign->id)}}" class="btn btn-secondary">   عرض  التسكين  </a></td>
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
