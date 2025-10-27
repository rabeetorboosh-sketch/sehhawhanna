<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            قائمة حركات الأصول
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn btn-save">
            <a href="{{ route('asset_movements.create') }}">
                إضافة حركة أصل <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الأصل</th>
                        <th>من </th>
                        <th>إلى </th>
                        <th>تاريخ ووقت النقل</th>
                        <th>السبب</th>
                        <th>حالة الأصل</th>
                        <th>وجهة النقل</th>
                        <th>المستخدم</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($movements as $movement)
                        <tr>
                            <td>{{ $movement->id }}</td>
                            <td>{{ $movement->asset->item->name }}</td>
                            <td>
                                @if($movement->from_item_type==4)
                                    {{ $movement->fromEmployee?->item?->name }}
                                @elseif($movement->from_item_type==8)
                                    {{ $movement->fromCustomer?->item?->name }}
                                @elseif($movement->from_item_type==9)
                                    {{ $movement->fromSupplier?->item?->name }}
                                @endif
                            </td>
                            <td>
                                @if($movement->to_item_type==4)
                                    {{ $movement->toEmployee?->item?->name }}
                                @elseif($movement->to_item_type==8)
                                    {{ $movement->toCustomer?->item?->name }}
                                @elseif($movement->to_item_type==9)
                                    {{ $movement->toSupplier?->item?->name }}
                                @endif
                            </td>

                            <td>{{ $movement->movement_datetime }}</td>
                            <td>{{ $movement->reason }}</td>
                            <td>
                                {{ $movement->asset_status?? ''}}
                            </td>
                            <td>{{ $movement->movement_destination }}</td>
                            <td>{{ $movement->user->name ?? 'غير محدد' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('asset_movements.show', $movement->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('asset_movements.edit', $movement->id) }}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $movement->id }}" action="{{ route('asset_movements.destroy', $movement->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $movement->id }})">حذف</button>
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

    <script>
        function confirmDelete(id) {
            if (confirm('هل أنت متأكد من حذف هذه الحركة؟')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
