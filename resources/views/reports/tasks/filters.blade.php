<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          تقارير المهام
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/report/filters-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/monitoring.css') }}">

    <div class="py-12">

        <form class="smart-form" action="{{ route('reportTasks.'.$url,$id??'') }}" method="get" enctype="multipart/form-data">


            <div class="row-5">
                <div class="form-group">
                    <label>المسند</label>
                    <select name="user_id">
                        <option value=""> الكل</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"  {{request('user_id')==$user->id?'selected':''}} >{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>من تاريخ </label>
                    <input type="date" name="from_date" value="{{request('from_date')}}">
                </div>
                <div class="form-group">
                    <label>الى تاريخ </label>
                    <input type="date" name="to_date" value="{{request('to_date')}}">
                </div>
                <div class="form-group">
                    <label>الموظف</label>
                    <select name="employee_id">
                        <option value=""> الكل</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"  {{request('employee_id')==$emp->id?'selected':''}} >{{ $emp->item->name }}</option>
                        @endforeach
                    </select>
                </div>
{{--                <div class="form-group">--}}
{{--                    <label> طريقة العرض </label>--}}
{{--                    <select name="summary">--}}
{{--                        <option value="0" {{request('summary')==0?'selected':''}}> تحليلي  </option>--}}
{{--                        <option value="1" {{request('summary')==1?'selected':''}}>اجمالي</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
            </div>
            <div class="row-5">
                <div class="form-group">
                    <label>الحل</label>
                    <select name="solved">
                        <option value="" > الكل</option>
                        <option value="1" {{request('solved')==1?'selected':''}} > المحلولة</option>
                        <option value="2" {{request('solved')==2?'selected':''}}>الغير محلولة</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>التحويل</label>
                    <select name="trans">
                        <option value="" > الكل</option>
                        <option value="1" {{request('trans')==1?'selected':''}} > المحولة للادارة</option>
                        <option value="2" {{request('trans')==2?'selected':''}}>الغير محولة</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>التجاوب</label>
                    <select name="responsiveness">
                        <option value="" > الكل</option>
                        <option value="1" {{request('responsiveness')==1?'selected':''}} > تم تجاوز وقتها</option>
                        <option value="2" {{request('responsiveness')==2?'selected':''}}>   بقي على وقتها </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>التكرار</label>
                    <select name="repeat">
                        <option value="" > الكل</option>
                        <option value="daily" {{request('repeat')=='daily'?'selected':''}} > يومي</option>
                        <option value="weekly" {{request('repeat')=='weekly'?'selected':''}}>اسبوعي</option>
                        <option value="monthly" {{request('repeat')=='monthly'?'selected':''}}>شهري</option>
                        <option value="null" {{request('repeat')=='null'?'selected':''}}>بدون تكرار</option>
                    </select>
                </div>


                <div class="row-3">
          <div class="form-group">
              <label style="color: transparent "> - </label>
              <button type="submit" class="btn btn-primary">فلترة</button>
          </div>

          <div class="form-group">
              <label style="color: transparent "> - </label>
              <a href="{{route('reportTasks.'.$url)}}" class= "btn btn-worn">  اعادة تعيين </a>
          </div>
          <div class="form-group">
              <label style="color: transparent "> - </label>
              <a href="{{route('reportTasks.print',request()->query())}}" class= "btn btn-secondary">  طباعة  </a>
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
