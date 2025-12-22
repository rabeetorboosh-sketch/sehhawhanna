<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            المجموعات الفرعية
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="add btn">
            <a href="{{route('PurSubGroup.add')}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>المجموعة الرئيسية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subGroups as $subGroup)
                        <tr>
                            <td>{{ $subGroup->id }}</td>
                            <td>{{ $subGroup->name }}</td>
                            <td>{{ $subGroup->mainGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{route('PurSubGroup.edit', [$subGroup->id])}}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $subGroup->id }}" action="{{ route('PurSubGroup.delete',[$subGroup->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $subGroup->id }})">
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
    <script src="{{asset('js/table.js')}}"></script>
</x-app-layout>
