<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          تقارير الرقابة اليومية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('reportMonitoring.index') }}" method="get" enctype="multipart/form-data">

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
                    <label>المجموعة الرئيسية</label>
                    <select name="mainGroup" id="mainGroupSelect">
                        <option selected value=""> اختر المجموعة الرئيسية </option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{ $mainGroup->id }}" data-section="{{ $mainGroup->department->id }}" {{request('mainGroup')==$mainGroup->id?'selected':''}}>
                                {{ $mainGroup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="subGroup" id="subGroupSelect">
                        <option value="" selected> اختر المجموعة الفرعية </option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{ $subGroup->id }}" data-main-group="{{ $subGroup->main_group_id }}" {{request('subGroup')==$subGroup->id?'selected':''}}>
                                {{ $subGroup->name }}
                            </option>
                        @endforeach
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
                <div class="form-group">
                    <label>المتسبب</label>
                    <select name="causer_id">
                        <option value=""> الكل</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"  {{request('causer_id')==$emp->id?'selected':''}} >{{ $emp->item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المشاكل</label>
                    <select name="issues">
                        <option value="0" {{request('issues')==0?'selected':''}}> الكل </option>
                        <option value="1" {{request('issues')==1?'selected':''}}>المشاكل فقط</option>
                    </select>
                </div>
                <div class="form-group">
                    <label> طريقة العرض </label>
                    <select name="summary">
                        <option value="0" {{request('summary')==0?'selected':''}}> تحليلي  </option>
                        <option value="1" {{request('summary')==1?'selected':''}}>اجمالي</option>
                    </select>
                </div>
      <div class="row-3">
          <div class="form-group">
              <label style="color: transparent "> - </label>
              <button type="submit" class="btn btn-primary">فلترة</button>
          </div>

          <div class="form-group">
              <label style="color: transparent "> - </label>
              <a href="{{route('reportMonitoring.index')}}" class= "btn btn-worn">  اعادة تعيين </a>
          </div>
          <div class="form-group">
              <label style="color: transparent "> - </label>
              <a href="{{route('reportMonitoring.print',request()->query())}}" class= "btn btn-secondary">  طباعة  </a>
          </div>

      </div>
            </div>



        </form>
    </div>
<div class="main-holder">
    @yield('tbl')
</div>


    <script src="{{ asset('js/report/filter.js') }}"></script>
    <script src="{{ asset('js/report/tableReport.js') }}"></script>

</x-app-layout>
