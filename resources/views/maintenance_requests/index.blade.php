<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            طلبات الصيانة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

    <div class="py-12">
        <div class="add btn">
            <a href="{{ route('maintenance_requests.create') }}">إضافة طلب <i class="fa-solid fa-plus"></i></a>
        </div>

        <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>الأصل</th>
                        <th>مقدم الطلب</th>
                        <th>البلاغ</th>
                        <th>المشكلة</th>
                        <th>نوع الطلب</th>
                        <th> الحالة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $request)

                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->user->name ?? '-' }}</td>
                            <td>{{ $request->asset->item->name ?? '-' }}</td>
                            <td>{{ $request->employee->item->name ?? '-' }}</td>
                            <td>{{ $request->report_id ? $request->report?->user_control_unit?? $request->report?->controlUnit?->name  : '-' }}</td>
                            <td>{{ Str::limit($request->issue_text, 30) }}</td>
                            <td style="background:  {{ $request->issueType?->color}};max-width: 100px; border-radius: 3px;">{{ $request->issueType?->name ?? '-' }}</td>
                            <td > @if($request->status==0) معلق
                                @elseif($request->status==1)
                                      في انتظار الاستلام
                                @elseif($request->status==2)
                                   تحت الصيانة
                                @endif
                            </td>
                            <td>
                                <div class="actions">

                                    <a href="{{ route('maintenance_requests.show', $request->id) }}" class="btn btn-primary">عرض</a>
                                    @if(Auth::user()->permissions('2-operations-maintenance_request')?->can_update == 1)
                                        <a href="{{ route('maintenance_requests.edit', $request->id) }}" class="btn btn-worn">تعديل</a>
                                    @endif
                                    @if(Auth::user()->permissions('2-operations-maintenance_request')?->can_approve == 1)
                                        <a href="{{ route('maintenance_requests.approve', $request->id) }}" class="btn btn-save">  {{$request->status==1?'الغاء الاعتماد':'اعتماد'}}</a>
                                    @endif
                                    @if(Auth::user()->permissions('2-operations-maintenance_request')?->can_delete == 1)
                                    <form id="delete-form-{{ $request->id }}" action="{{ route('maintenance_requests.destroy', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $request->id }})">
                                            حذف
                                        </button>
                                    </form>
                                    @endif
                                    @if(Auth::user()->permissions('2-operations-maintenance')?->can_create == 1)
                                    @if($request->status==1)
                                        <a href="{{ route('maintenance_solutions.create', $request->id) }}" class="btn btn-secondary">حل</a>
                                    @endif
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
