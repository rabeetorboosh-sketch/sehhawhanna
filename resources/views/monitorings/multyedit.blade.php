@extends('layouts.monitor')
<link href="{{ asset('css/monitorings.css') }}" rel="stylesheet">
@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">اكمال تقرير المراقبة <i class="fas fa-file-alt"></i> </h1>

        <div class="sections-bar">
            <div class="sections-container">
                @foreach($sections as $section)

                    <button class="section-lbl" value="{{$section->id}}">
                        {{$section->name}}      <i class="fas fa-list"></i>

                    </button>
                @endforeach
            </div>
        </div>

        <form action="{{ route('monitorings.multyupdate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="reportId" value="{{$monitoringReportId}}">
            <div class="mb-4">

                <div id="product-fields">
                    <div class="flex items-center mb-4" id="product-field-1">
                        <div class="searchable-select">
                            @foreach($items as $pro)
                                <div class="grp{{$pro->monitoring_department_id}} item-container" style="  margin-bottom: 1px;">
                                    <input value="{{ $pro->name }}" class="product-name" style=" border: none" disabled>
                                    <input name="item_id[]" value="{{ $pro->id }}" class=" vlue-lbl">
                                    <div class="flex space-x-4">
                                        <input type="checkbox" data-text="{{$pro->id}}" name="is_correct[]" value="{{$pro->id}}" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm mx-2 correct" >
                                    </div>
                                    <textarea name="issue_text[] " class="txt{{$pro->id}}">  </textarea>
                                    <div style="margin-top: 1px;">
                                        <!-- إخفاء input الملف -->
                                        <input type="file" accept="image/*" name="image[]" id="image-{{$pro->id}}" class="hidden">

                                        <!-- إنشاء label مخصص -->
                                        <label for="image-{{$pro->id}}" style="
                                        display: inline-block;
                                        padding: 5px 5px;
                                        background-color: #494c50;
                                        color: white;
                                        border-radius: 5px;
                                        cursor: pointer;
                                        font-size: 8px;
                                    ">
                                            <i class="fas fa-camera"></i> إضافة صورة
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-save"></i>    حفظ

            </button>
        </form>
    </div>

    <script>





        document.querySelectorAll('.section-lbl').forEach(button => {
            button.addEventListener('click', function(e) {

                var inp = document.querySelectorAll('.item-container');

                inp.forEach(input => {
                    input.style.display = 'none';

                });


                var inp = document.querySelectorAll(`.grp${e.target.value}`);

                inp.forEach(input => {
                    input.style.display = 'flex';  // تصحيح "disbly" إلى "display"
                });

            });
        });


        document.querySelectorAll('.correct').forEach(button => {
            button.addEventListener('change', function(e) {
                const cls = e.target.getAttribute('data-text');
                const txt = document.querySelector(`.txt${cls}`);

                if (txt) {
                    if (e.target.checked) {
                        txt.classList.add('collapsed');
                        txt.value="";
                    } else {
                        txt.classList.remove('collapsed');
                    }
                }
            });
        });




    </script>





@endsection

