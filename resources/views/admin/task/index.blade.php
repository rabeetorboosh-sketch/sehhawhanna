<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            المهام
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('tasks.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المهمة</th>
                        <th>وصف المهمة</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->item->name }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->item->mainGroup->name ?? '' }}</td>
                            <td>{{ $task->item->subGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $task->id }})">حذف</button>
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
