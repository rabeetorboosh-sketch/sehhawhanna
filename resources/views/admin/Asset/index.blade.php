<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            الأصول
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        @if(Auth::user()->permissions('2-insertions-assets')?->can_create == 1)
        <div class="add btn">
            <a href="{{route('asset.add')}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        @endif
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم التعريفي</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>تاريخ الاستخدام</th>
                        <th>العمر الافتراضي</th>
                        <th>الوصف</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assets as $asset)
                        <tr>
                            <td>{{ $asset->id }}</td>
                            <td>{{ $asset->item->name }}</td>
                            <td>{{ $asset->id_number }}</td>
                            <td>{{ $asset->item->mainGroup->name ?? '' }}</td>
                            <td>{{ $asset->item->subGroup->name ?? '' }}</td>

                            <td>{{ $asset->usage_date }}</td>
                            <td>{{ $asset->lifetime }} سنة/سنوات </td>
                            <td>{{ $asset->description }}</td>
                            <td>
                                <div class="actions">
                                    @if(Auth::user()->permissions('2-insertions-assets')?->can_update == 1)
                                    <a href="{{route('asset.edit', $asset->id)}}" class="btn btn-worn">تعديل</a>
                                    @endif
                                    <a href="{{route('asset.show', $asset->id)}}" class="btn btn-primary">عرض</a>
                                        @if(Auth::user()->permissions('2-insertions-assets')?->can_delete == 1)
                                    <form id="delete-form-{{ $asset->id }}" action="{{ route('asset.delete', $asset->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $asset->id }})">
                                            حذف
                                        </button>
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
    <script src="{{asset('js/table.js')}}"></script>
</x-app-layout>
