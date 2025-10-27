<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            اضافة مجموعة رئيسية
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{session('success')}}
            </div>
        @endif
        <form class="smart-form" action="{{route('mainGroup.update',$mainGroup->id)}}" method="post">
            @csrf
            <div class="row-2">
                <div class="form-group">
                    <label>اسم المجموعة </label>
                    <input name="name" type="text" autocomplete="off"  value="{{$mainGroup->name}}">
                </div>
                <div class="form-group">
                    <label>الــقــســم </label>
                    <select name="department">
                        <option disabled selected>اختر القسم</option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}" {{$department->id ==$mainGroup->department->id?'selected':''}}> {{$department->name}}  </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script  src="{{asset('js/form.js')}}"></script>
</x-app-layout>
