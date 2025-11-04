<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            التقييمات
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('ratings.create') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الموظف</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                        <th>التقييم العام</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->item->name ?? '-' }}</td>
                            <td>{{ $rating->user->name ?? '-' }}</td>
                            <td>{{ $rating->date }}</td>
                            <td>
                                @php
                                    $itemsNum = 0;
                                    $itemVal = 0;
                                @endphp
                                @foreach($rating->items as $item)
                                    @php
                                        $multiply = optional($item->ratingUnit)->multiply ?? 0;
                                        $itemsNum += $item->percentage * $multiply;
                                        $itemVal += $multiply;
                                    @endphp
                                @endforeach
                                <div>{{ $itemVal > 0 ? round($itemsNum / $itemVal, 2) : 0 }}%</div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('ratings.show', $rating->id) }}" class="btn btn-primary">عرض</a>

                                    @if(Auth::user()->permissions('4-operations-ratings')?->can_update == 1)
                                        <a href="{{ route('ratings.edit', $rating->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif

                                    @if(Auth::user()->permissions('4-operations-ratings')?->can_delete == 1)
                                        <form id="delete-form-{{ $rating->id }}" action="{{ route('ratings.destroy', $rating->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $rating->id }})">حذف</button>
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
