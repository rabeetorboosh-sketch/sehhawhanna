<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
               المجموعات الرئيسية
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="add btn">
            <a href="{{route('purMainGroup.add',$department??'')}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table" id="orders">
                    <thead>
                    <tr>

                        <th>#</th>
                        <th>الاسم </th>

                        <th>العمليات </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($mainGroups as $mainGroup)
                        <tr>

                            <td >{{$mainGroup->id}}</td>
                            <td >{{$mainGroup->name}}</td>

                            <td >
                                <div class="actions">
                                    <a href="{{route('purMainGroup.edit', ['purMainGroup' => $mainGroup->id])}}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $mainGroup->id }}" action="{{ route('purMainGroup.delete', [$mainGroup->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $mainGroup->id }})">
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
