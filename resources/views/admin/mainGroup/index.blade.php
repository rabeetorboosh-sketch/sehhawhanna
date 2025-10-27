<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
               المجموعات الرئيسية
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="add btn">
            <a href="{{route('mainGroup.add',$department??'')}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table" id="orders">
                    <thead>
                    <tr>

                        <th>#</th>
                        <th>الاسم </th>
                        <th>القسم</th>
                        <th>العمليات </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($mainGroups as $mainGroup)
                        <tr>

                            <td >{{$mainGroup->id}}</td>
                            <td >{{$mainGroup->name}}</td>
                            <td>
                                {{$mainGroup->department->name ?? ''}}
                             </td>
                            <td >
                                <div class="actions">
                                    <a href="{{route('mainGroup.edit',  ['mainGroup' => $mainGroup->id, 'department' => $department ?? null])}}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $mainGroup->id }}" action="{{ route('mainGroup.delete', [$mainGroup->id,$department]) }}" method="POST" style="display: inline;">
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
