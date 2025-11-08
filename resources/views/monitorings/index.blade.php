<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            الرقابات اليومية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('monitoring.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>اليوم</th>
                        <th>عدد الوحدات</th>
                        <th>عدد البنود</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($dailyControls as $control)

                        @php
                            $totalUnits = $control->items->pluck('control_unit_id')->unique()->count();
                            $totalItems = $control->items->count();
                        @endphp
                        <tr>
                            <td>{{ $control->id }}</td>
                            <td>{{ $control->user?->name }}</td>
                            <td>{{ $control->day }}</td>
                            <td>{{ $totalUnits }}</td>
                            <td>{{ $totalItems }}</td>
                            <td>
                                @if($control->items->every(fn($item) => $item->is_correct))
                                    <span class="text-green-600">سليم</span>
                                @elseif($control->items->every(fn($item) => !$item->is_correct))
                                    <span class="text-red-600">مشكلة</span>
                                @else
                                    <span class="text-yellow-600">مختلط</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('monitoring.show', $control->id) }}" class="btn btn-primary">عرض</a>
                                    <a href="{{ route('monitoring.edit', $control->id) }}" class="btn btn-wo  rn">اكمال</a>
                                    @if(Auth::user()->permissions('daily_monitoring-daily_monitoring')?->can_delete == 1)
                                    <form id="delete-form-{{ $control->id }}" action="{{ route('monitoring.destroy', $control->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $control->id }})">حذف</button>
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
