<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض تقرير المشرف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">بيانات التقرير</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">اسم العميل</span>
                        <span class="info-content">{{ $supervise->customer->item->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الموظف</span>
                        <span class="info-content">{{ $supervise->employee->item->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">اسم الشخص</span>
                        <span class="info-content">{{ $supervise->name }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">رقم الهاتف</span>
                        <span class="info-content">{{ $supervise->phone }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">المشكلة</span>
                        <span class="info-content">{{ $supervise->issue ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">طريقة الحل</span>
                        <span class="info-content">{{ $supervise->solution_method ?? '—' }}</span>
                    </div>
                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @if($supervise->is_completed)
                                <span class="status completed">تم الإنجاز</span>
                            @elseif($supervise->transferred_to_management)
                                <span class="status transferred">محولة للإدارة</span>
                            @else
                                <span class="status pending">قيد المعالجة</span>
                            @endif
                        </span>
                    </div>
                    @if($supervise->delay_reason)
                        <div class="info-card">
                            <span class="info-title">سبب التأجيل</span>
                            <span class="info-content">{{ $supervise->delay_reason }}</span>
                        </div>
                    @endif
                    @if($supervise->transfer_reason)
                        <div class="info-card">
                            <span class="info-title">سبب التحويل</span>
                            <span class="info-content">{{ $supervise->transfer_reason }}</span>
                        </div>
                    @endif
                </div>

                {{-- الموقع --}}
                <h3 class="section-title">الموقع على الخريطة</h3>
                @if($supervise->location)
                    <div class="location-map" style="max-width: 100% ">

                        <iframe
                            class="mapshow"
                            id="mapFrame"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            location-data="{{ $supervise->location }}"
                        ></iframe>
                    </div>


                @else
                    <span>لم يتم تحميل الموقع</span>
                @endif


                {{-- الصور --}}
                @if(isset($supervise->media) && $supervise->media->isNotEmpty())
                    <h3 class="section-title">الصور</h3>
                    <div class="media-grid">
                        @foreach($supervise->media as $media)
                            <a href="{{ asset($media->url) }}" target="_blank">
                                <img src="{{ asset($media->url) }}" alt="صورة التقرير" class="media-thumb">
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('supervises.index') }}" class="btn-back">العودة للتقارير</a>
                </div>

            </div>
        </div>
    </div>

    @if($supervise->location)
         <script>
            const iframe = document.getElementById("mapFrame");

            // Get coordinates from the custom attribute
            const coordinates = iframe.getAttribute("location-data");

            // Set the iframe src using the coordinates
            iframe.src = `https://maps.google.com/maps?q=${coordinates}&z=15&output=embed`;
        </script>
    @endif
</x-app-layout>
