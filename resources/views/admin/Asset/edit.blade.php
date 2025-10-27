<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل الأصل
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <div class="py-12">
        <form class="smart-form" action="{{route('asset.update',$asset->id)}}" method="post">
            @csrf
            <div class="row-2">
                <div class="form-group">
                    <label>اســـــــم الأصــــل</label>
                    <input name="name" type="text" autocomplete="off" value="{{$asset->item->name}}">
                </div>
                <div class="form-group">
                    <label>  الــرقـم الـتعـريـفي</label>
                    <input name="id_number" type="text" autocomplete="off" value="{{$asset->id_number}}">
                </div>
                <div class="form-group">
                    <label>المجموعة الرئيسية</label>
                    <select name="main_group">
                        <option disabled selected>اختر المجموعة</option>
                        @foreach($mainGroups as $mainGroup)
                            <option value="{{$mainGroup->id}}" {{$mainGroup->id == $asset->item->main_group_id ? 'selected':''}}>
                                {{$mainGroup->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>المجموعة الفرعية</label>
                    <select name="sub_group">
                        <option disabled selected>اختر المجموعة الفرعية</option>
                        @foreach($subGroups as $subGroup)
                            <option value="{{$subGroup->id}}" {{$subGroup->id == $asset->item->sub_group_id ? 'selected':''}}>
                                {{$subGroup->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>تاريـخ الاسـتـخــدام</label>
                    <input type="date" name="usage_date" value="{{$asset->usage_date}}">
                </div>
                <div class="form-group">
                    <label>الـعـمر الافـتراضـي</label>
                    <input type="number" name="lifetime" value="{{$asset->lifetime}}">
                </div>
                <div class="form-group">
                    <label>الــــــــــوصـــــــف</label>
                    <textarea name="description">{{$asset->description}}</textarea>
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
            </div>
        </form>
    </div>
    <script src="{{asset('js/form.js')}}"></script>
</x-app-layout>
