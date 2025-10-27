<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            وحدات المراقبة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('controlUnit.create',$department??'') }}">إضافة <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الوحدة</th>
                        <th>نوع المشكلة</th>
                        <th>القسم</th>
                        <th>المجموعة الرئيسية</th>
                        <th>المجموعة الفرعية</th>
                        <th>صور</th>
                        <th>  ضمن الرقابة اليومية</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($controlUnits as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->issueType->name ?? '' }}</td>
                            <td>{{ $unit->section->name ?? '' }}</td>
                            <td>{{ $unit->mainGroup->name ?? '' }}</td>
                            <td>{{ $unit->subGroup->name ?? '' }}</td>
                            <td>
                                @if( $unit->has_photos  ==1)
                                    لها صورة واحدة
                                @elseif( $unit->has_photos  ==2)
                                    لها  العديد من الصور
                                @else
                                    ليس لها صور
                                @endif
                                 </td>
                            <td>{{ $unit->daily_control ? 'نعم' : 'لا' }}</td>
                            <td>
                                <div class="actions">


                                    <a href="{{ route('controlUnit.edit', [$unit->id,$department??'']) }}" class="btn btn-worn">تعديل</a>

                                    <form id="delete-form-{{ $unit->id }}" action="{{ route('controlUnit.destroy', [$unit->id,$department??'']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $unit->id }})">حذف</button>
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
