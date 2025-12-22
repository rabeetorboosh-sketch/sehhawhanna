<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الأصناف
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        @if(Auth::user()->permissions('1-insertions-products')?->can_create == 1)
        <div class="add btn">
            <a href="{{ route('pur_items.create') }}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        @endif
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الكود</th>
                        <th>الوصف</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($product as $item)

                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->mainGroup->name ?? '' }}</td>
                            <td>{{ $item->subGroup->name ?? '' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{route('pur_items.show', $item->id)}}" class="btn btn-primary">عرض</a>
                                    @if(Auth::user()->permissions('1-insertions-products')?->can_update == 1)
                                    <a href="{{ route('pur_items.edit', $item->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                        @if(Auth::user()->permissions('1-insertions-products')?->can_delete == 1)
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('pur_items.delete', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $item->id }})">حذف</button>
                                    </form>
                                        @endif
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

