<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            اضافة مجموعة فرعية
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form class="smart-form" action="{{route('subGroup.create')}}" method="post">
            @csrf
            <div class="row-2">
                <input type="hidden" value="{{$department??''}}" name="department">
                <div class="form-group">
                    <label>اســـــم المجموعة</label>
                    <input name="name" type="text" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group">
                        <option disabled selected>اختر المجموعة الرئيسية</option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{$mainGroup->id}}">{{$mainGroup->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script src="{{asset('js/form.js')}}"></script>
</x-app-layout>
