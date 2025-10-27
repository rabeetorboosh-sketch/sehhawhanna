<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل انواع مشاكل
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form class="smart-form" action="{{route('issuesType.update')}}" method="post">
            @csrf
            <div class="row-2">
                <input name="id" type="hidden" autocomplete="off" value="{{$issueType->id}}">
                <div class="form-group">
                    <label>اســــم الـــنـــوع </label>
                    <input name="name" type="text" autocomplete="off" value="{{$issueType->name}}">
                </div>
                <div class="form-group">
                    <label> الــــلــــــون</label>
                    <input name="color" type="color" class="color" value="{{$issueType->color}}">
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script  src="{{asset('js/form.js')}}"></script>
</x-app-layout>
