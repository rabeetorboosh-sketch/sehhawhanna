{{-- resources/views/maintenance_solutions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            عرض حل طلب صيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/task_assignments.css') }}">

    <div class="task-container">
        <h3>تفاصيل حل طلب الصيانة</h3>

        <div class="task-details">

            <strong>سبب المشكلة:</strong>
            <div class="readonly">{{ $solution->issue_reason ?? '-' }}</div>

            <strong>حل المشكلة:</strong>
            <div class="readonly">{{ $solution->solution_text ?? '-' }}</div>

            <strong>الوقت المستغرق لحل المشكلة (ساعات):</strong>
            <div class="readonly">{{ $solution->time_spent ?? '-' }}</div>

            <strong>القطع الغير صالحة:</strong>
            <div class="readonly">{{ $solution->bad_parts ?? '-' }}</div>

            <strong>اسم الورشة:</strong>
            <div class="readonly">{{ $solution->workshop_name ?? '-' }}</div>

            <strong>اسم مسئول الصيانة:</strong>
            <div class="readonly">{{ $solution->maintenance_responsible ?? '-' }}</div>

            <strong>تكلفة الإصلاح:</strong>
            <div class="readonly">{{ $solution->repair_cost ?? '-' }}</div>

            <strong>هل الحل مؤقت؟</strong>
            <div class="readonly">{{ $solution->temporary_solution ? 'نعم' : 'لا' }}</div>

            <strong>الضمان:</strong>
            <div class="readonly">

                @if($solution->has_warranty==1)
                    نعم<br>
                    نوع الضمانة: {{ $solution->warranty_type ?? '-' }}<br>
                    صلاحية الضمان: {{ $solution->warranty_expiry ?? '-' }}
                @else
                    لا
                @endif
            </div>

            <strong>هل تم تسليمها؟</strong>
            <div class="readonly">{{ $solution->delivered ? 'نعم' : 'لا' }}</div>
        </div>

        <div class="task-actions">
            <a href="{{ route('maintenance_solutions.index') }}" class="btn-secondary">رجوع</a>
            <a href="{{ route('maintenance_solutions.edit', $solution->id) }}" class="btn-worn">تعديل</a>
        </div>
    </div>
</x-app-layout>
