<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            انواع المشاكل
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="add btn">
            <a href="{{route('products.add')}}">اضافة <i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table" id="orders">
                    <thead>
                    <tr>

                        <th>#</th>
                        <th>النوع</th>
                        <th>الللون</th>
                        <th>عمليات</th>

                    </tr>
                    </thead>
                    <tbody>

                    @foreach($issueTypes as $issueType)
                        <tr>

                            <td >{{$issueType->id}}</td>
                            <td >{{$issueType->name}}</td>
                            <td>
                                <div style="width: 30px; height: 20px; background-color: {{ $issueType->color }}; border-radius: 4px; border: 1px solid #ccc;"></div>
                            </td>
                            <td >
                                <div class="actions">
                                    <a href="{{route('issuesType.edit',$issueType->id)}}" class="btn btn-worn">تعديل</a>
                                    <form id="delete-form-{{ $issueType->id }}" action="{{ route('issuesType.delete', $issueType->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $issueType->id }})">
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
