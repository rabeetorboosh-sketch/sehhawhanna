<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض تفاصيل التقييم
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                {{-- بيانات التقييم --}}
                <h3 class="section-title">بيانات التقييم</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">المستخدم</span>
                        <span class="info-content">{{ $rating->user->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الموظف</span>
                        <span class="info-content">{{ $rating->item->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">التاريخ</span>
                        <span class="info-content">{{ $rating->date }}</span>
                    </div>
                </div>

                {{-- جدول الوحدات --}}
                <h3 class="section-title">تفاصيل التقييم</h3>

                <div class="overflow-x-auto bg-white shadow rounded-lg mt-4">
                    <table class="w-full text-center border border-gray-200">
                        <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">#</th>
                            <th class="py-3 px-4 border">الوحدة</th>
                            <th class="py-3 px-4 border">النسبة (%)</th>
                            <th class="py-3 px-4 border">الوزن</th>
                        </tr>
                        </thead>
                        <tbody>
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
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border">{{ $item->ratingUnit->name ?? '-' }}</td>
                                <td class="py-2 px-4 border">{{ $item->percentage }}%</td>
                                <td class="py-2 px-4 border">{{ $item->ratingUnit->multiply }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold">
                        @php
                            $finalPercentage = $totalMultiply > 0 ? $totalWeighted / $totalMultiply : 0;
                        @endphp
                        </tfoot>
                    </table>
                </div>

                {{-- التقييم العام --}}
                <h3 class="section-title mt-6">التقييم العام</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">النسبة النهائية</span>
                        <span class="info-content">{{ number_format($finalPercentage, 2) }}%</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">التصنيف</span>
                        <span class="info-content">
                            @if($finalPercentage >= 90)
                                <span class="status completed">ممتاز</span>
                            @elseif($finalPercentage >= 75)
                                <span class="status transferred">جيد جدًا</span>
                            @elseif($finalPercentage >= 60)
                                <span class="status pending">جيد</span>
                            @else
                                <span class="status alert">ضعيف</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ $back_url }}" class="btn btn-primary">عودة</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
