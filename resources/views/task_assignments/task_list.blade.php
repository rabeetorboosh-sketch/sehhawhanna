<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إسناد المهام
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('task_assignments.list') }}" method="get" enctype="multipart/form-data">

            <div class="row-3">
                <div class="form-group">
                    <label>من تاريخ </label>
                    <input type="date" name="from_date" value="{{request('from_date')}}">
                </div>
                <div class="form-group">
                    <label>الى تاريخ </label>
                    <input type="date" name="to_date" value="{{request('to_date')}}">
                </div>
                <div class="form-group">
                    <label>البند</label>
                    <input type="text" id="itemInput" placeholder="ابحث عن بند"
                           value="{{ optional($items->where('id', request('item_id'))->first())->name?? 'كل البنود'}}">
                    <input type="hidden" name="item_id" id="itemId" value="{{ request('item_id') }}">
                    <select id="itemSelect" size="5" class="item-search">
                        <option value=" "    >كل البنود</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name ?? 'بدون اسم' }}
                            </option>
                        @endforeach
                    </select>
                </div>


            </div>
            <div class="row-3">
                <div class="form-group">
                    <label>القسم</label>
                    <select name="section_id" id="sectionSelect" >
                        <option value="">كل الاقسام </option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}" {{request('section_id')==$department->id?'selected':''}}>{{$department->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label> الحالة </label>
                    <select name="assigned" id="sectionSelect" >
                        <option value="">الكل   </option>
                        <option value="1" {{request('assigned')==1?'selected':''}}> الغير مسندة من قبل  </option>
                    </select>
                </div>
            </div>

            <div class="row-5">

                <div class="form-group">
                    <label>الوحدة الرقابية</label>
                    <select name="control_unit_id" class="control-unit-select">
                        <option value="" >      فلترة حسب الوحدة </option>
                        @foreach($controlUnits as $unit)
                            <option value="{{ $unit->id }}"
                                    data-photo="{{$unit->has_photos}}"
                                    data-department="{{$unit->department_id}}"
                                    data-main-group="{{$unit->main_group_id}}"
                                    data-sub-group="{{$unit->sub_group_id}}"
                                {{request('control_unit_id')==$unit->id?'selected':''}}
                            >
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row-3">
                    <div class="form-group">
                        <label style="color: transparent "> - </label>
                        <button type="submit" class="btn btn-primary">فلترة</button>
                    </div>

                    <div class="form-group">
                        <label style="color: transparent "> - </label>
                        <a href="{{route('task_assignments.list')}}" class= "btn btn-worn">  اعادة تعيين </a>
                    </div>
                </div>
            </div>



        </form>
    </div>
    <form action="{{route('taskAssignment.assign')}}" method="post">
        @csrf
    <div class="py-12">
        <input type="submit" class="btn btn-worn" value=" اسناد المحدد">
        <br>
        <div class="table-title">المهام</div>
   <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>الوصف</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td class="ten">{{ $task->id }}</td>
                            <td class="four">{{($task->user_control_unit)?$task?->user_control_unit."-": $task?->controlUnit?->name ."-"}}  {{ $task?->item?->name  }}  </td>
                            <td class="four">{{ $task->description }}</td>

                            <td>
                                <div class="actions">

                                     <input name="tasks[{{$task->id}}]" type="checkbox" class="checkbox btn " value="{{$task->id}}"> تحديد
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>



        </div>
        <br>
<div class="table-title"> البلاغات</div>
   <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th> وصف المهمة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                        @foreach($report->items as $item)

                        <tr>
                            <td class="ten">{{ $item->id ??''}}</td>
                            <td class="four">

                                @if($item->control_unit_id!=null)
                                    <div class="item-card">

                                       {{$item->controlUnit->name .'->'. $item->item?->name }}

                                    </div>
                                @elseif($item->user_control_unit!=null)

                                    <div class="item-card">

                                           {{$item->user_control_unit .'->'. $item->item?->name}}

                                    </div>

                                @endif
                            </td>
                            <td class="four">{{ $item->issue_description ??''}}</td>

                            <td>
                                <div class="actions">
                                    <input type="checkbox"
                                           data-date="{{ $item->created_at }}"
                                           class="btn checkbox"
                                           name="reports[{{ $item->id }}]"
                                           value="{{ $item->id }}"> تحديد
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>



        </div>
        <br>
        <div class="table-title">عناصر الرقابة</div>
   <div class="table-wrap">
            <div class="table-scroll">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>المهمة</th>
                        <th>وصف المهمة</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($monitorings as $monitoring)
                        @foreach($monitoring->items as $item)

                        <tr>
                            <td class="ten">{{ $item->dailyControl_id ??''}}</td>
                            <td class="four"> @if($item->dailyControl_id != null)
                                    <div class="item-card">
                                        <label>

                                            <span>{{ $item->controlUnit?->name .' -> '. $item->item?->name }}</span>
                                        </label>
                                    </div>
                                @endif

                            </td>
                            <td class="four">{{ $item->description ??''}}</td>

                            <td>
                                <div class="actions">
                                    <input type="checkbox"
                                           data-date="{{ $item->created_at }}"
                                           class="btn checkbox"
                                           name="monitorings[{{ $item->id }}]"
                                           value="{{ $item->id }}"> تحديد
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>



        </div>
    </div>
    </form>
    <script src="{{ asset('js/report/filter.js') }}"></script>
    <script src="{{ asset('js/table.js') }}"></script>
</x-app-layout>
