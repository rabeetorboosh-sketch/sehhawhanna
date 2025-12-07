<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            عرض استلام المهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="report-container">

                <h3 class="section-title">تفاصيل الاستلام</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <span class="info-title">المهمة</span>
                        <span class="info-content">{{ $taskReceipt->assignment->task->item->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الموظف</span>
                        <span class="info-content">{{ $taskReceipt->employee?->item->name ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">وقت الاستلام</span>
                        <span class="info-content">{{ $taskReceipt->received_at ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">التقييم</span>
                        <div class="progress-container">
                            <div class="progress-bar"
                                 style="width: {{ $taskReceipt->completion_percentage ?? 0 }}%;
            background-color:
            @if(($taskReceipt->completion_percentage ?? 0) < 40) #e74c3c
            @elseif(($taskReceipt->completion_percentage ?? 0) < 70) #f1c40f
            @else #2ecc71
            @endif;">
                            </div>
                            <span class="progress-text">{{ $taskReceipt->completion_percentage ?? 0 }}%</span>
                        </div>
                    </div>

                    <div class="info-card">
                        <span class="info-title">الحالة</span>
                        <span class="info-content">
                            @if($taskReceipt->is_completed)
                                <span class="status completed">مكتملة</span>
                            @else
                                <span class="status pending">قيد التنفيذ</span>
                            @endif
                        </span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">طريقة الحل</span>
                        <span class="info-content">{{ $taskReceipt->solution_method ?? '-' }}</span>
                    </div>

                    <div class="info-card">
                        <span class="info-title">محولة للإدارة</span>
                        <span class="info-content">{{ $taskReceipt->forwarded_to_management ? 'نعم' : 'لا' }}</span>
                    </div>

                    @if($taskReceipt->forward_reason)
                        <div class="info-card">
                            <span class="info-title">سبب التحويل</span>
                            <span class="info-content">{{ $taskReceipt->forward_reason }}</span>
                        </div>
                    @endif

                    @if($taskReceipt->notes)
                        <div class="info-card">
                            <span class="info-title">الملاحظات</span>
                            <span class="info-content">{{ $taskReceipt->notes }}</span>
                        </div>
                    @endif

                    @if($taskReceipt->location)
                        <div class="info-card">
                            <span class="info-title">الموقع</span>
                            <span class="info-content">{{ $taskReceipt->location }}</span>
                        </div>
                    @endif
                </div>

                {{-- الصور --}}
                @if(isset($taskReceipt->media) && $taskReceipt->media->isNotEmpty())
                    <h3 class="section-title">الصور</h3>
                    <div class="media-grid">
                        @foreach($taskReceipt->media as $media)
                            <a href="{{ asset($media->url) }}" target="_blank">
                                <img src="{{ asset($media->url) }}" alt="صورة الاستلام" class="media-thumb">
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- ملفات الوورد --}}
                @if(isset($taskReceipt->file) && $taskReceipt->file->isNotEmpty())
                    <h3 class="section-title">الملفات </h3>
                    <ul class="file-list">
                        @foreach($taskReceipt->file as $file)
                            <li>
                                <a href="{{ $file->url}}" target="_blank" CLASS="
                                btn btn-info">
                                    <i class="fa fa-file" aria-hidden="true"></i>
                                 عرض الملف
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('task_receipts.edit', $taskReceipt->id) }}" class="btn btn-worn">تعديل</a>
                    <a href="{{ session('old_TaskReceipt_url', url()->previous()) }}" class="btn btn-secondary">رجوع</a>
                    @if(Auth::user()->permissions('5-operations-receipts')?->can_approve == 1)
                        <button type="button" class="btn btn-primary" id="show-rate-form">تقييم</button>
                        <form id="rate-form" method="post" action="{{route('task_receipts.rate',$taskReceipt->id)}}" style="display:none">
                            @csrf
                            <input type="number" name="percentage" placeholder="ضع تقييم من 1 الى 100" max="100" step="1" required>
                            <button class="btn btn-save">حفظ</button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        .progress-container {
            position: relative;
            width: 100%;
            height: 25px;
            background: #f0f0f0;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            height: 100%;
            transition: width 0.5s ease;
            background: linear-gradient(90deg, #4caf50, #81c784);
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 14px;
            color: #fff;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .media-thumb {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .file-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .file-list li {
            margin-bottom: 5px;
        }

        .file-list a {
            text-decoration: none;
            color: #2c3e50;
        }

        .file-list a i {
            margin-right: 5px;
            color: #0078d7; /* لون أيقونة الوورد */
        }
        a.btn.btn-info {
            background: linear-gradient(65deg, #a3cdbc, #9fc1b29e);
            border: 2px inset #bfd7cc;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const showBtn = document.getElementById("show-rate-form");
            const rateForm = document.getElementById("rate-form");

            showBtn.addEventListener("click", function () {
                if (rateForm.style.display === "none") {
                    rateForm.style.display = "block";
                    showBtn.textContent = "إلغاء";
                } else {
                    rateForm.style.display = "none";
                    showBtn.textContent = "تقييم";
                }
            });
        });
    </script>
</x-app-layout>
