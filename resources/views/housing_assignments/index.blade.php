<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            عمليات التسكين
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">

        {{-- زر الإضافة --}}
        <div class="add btn">
            <a href="{{ route('housing_assignments.create') }}">
                اضافة  <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الوحدة السكنية</th>
                        <th>عدد الموظفين</th>

                        <th>تاريخ التسكين</th>
                        <th>ملاحظات</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($assignments as $assignment)
                        <tr>
                            <td>{{ $assignment->id }}</td>

                            {{-- اسم الوحدة --}}
                            <td>{{ $assignment->unit?->name ?? '-' }}</td>

                            {{-- عدد الموظفين --}}
                            <td>{{ $assignment->items?->count() ?? 0 }}</td>



                            {{-- تاريخ التسكين --}}
                            <td>{{ $assignment->assignment_date ?? $assignment->created_at->format('Y-m-d') }}</td>

                            {{-- ملاحظات --}}
                            <td>{{ $assignment->notes ?? '-' }}</td>

                            {{-- العمليات --}}
                            <td>
                                <div class="actions">

                                    <a href="{{ route('housing_assignments.show', $assignment->id) }}"
                                       class="btn btn-primary">عرض</a>

                                    <a href="{{ route('housing_assignments.edit', $assignment->id) }}"
                                       class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $assignment->id }}"
                                          action="{{ route('housing_assignments.destroy', $assignment->id) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete({{ $assignment->id }})">
                                            حذف
                                        </button>
                                    </form>

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
