<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            تعديل الوحدة السكنية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">

        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{route('housing_units.update', $unit->id)}}" method="post">
            @csrf
            @method('PUT')

            <div class="row-2">

                <div class="form-group">
                    <label>كود الوحدة</label>
                    <input name="unit_code" type="text" value="{{$unit->unit_code}}">
                </div>

                <div class="form-group">
                    <label>اسم الوحدة</label>
                    <input name="name" type="text" value="{{$unit->name}}">
                </div>

                <div class="form-group">
                    <label>نوع الوحدة</label>
                    <select name="unit_type">
                        <option disabled>اختر النوع</option>
                        <option value="شقة" {{ $unit->unit_type=='شقة' ? 'selected':'' }}>شقة</option>
                        <option value="فيلا" {{ $unit->unit_type=='فيلا' ? 'selected':'' }}>فيلا</option>
                        <option value="عمارة" {{ $unit->unit_type=='عمارة' ? 'selected':'' }}>عمارة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>عدد المطابخ</label>
                    <input name="total_kitchens" type="number" value="{{$unit->total_kitchens}}">
                </div>

                <div class="form-group">
                    <label>عدد الحمامات</label>
                    <input name="total_bathrooms" type="number" value="{{$unit->total_bathrooms}}">
                </div>

                <div class="form-group">
                    <label>العنوان</label>
                    <input name="address" type="text" value="{{$unit->address}}">
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes">{{$unit->notes}}</textarea>
                </div>

                <!-- ╔═══════════════ غرف الوحدة (تعديل) ═══════════════╗ -->
                <div class="form-group" style="border:1px solid gray;border-radius:10px;padding:5px">

                    <h3>الغرف</h3>

                    <div id="rooms-wrapper">

                        @foreach($unit->rooms as $index => $room)
                            <div class="room-row">
                                <div class="row-3">

                                    <input type="hidden" name="rooms[{{$index}}][id]" value="{{$room->id}}">

                                    <div class="form-group">
                                        <label>اسم الغرفة</label>
                                        <input type="text" name="rooms[{{$index}}][room_name]" value="{{$room->room_name}}">
                                    </div>

                                    <div class="form-group">
                                        <label>عدد الأسرة</label>
                                        <input type="number" name="rooms[{{$index}}][bed_count]" value="{{$room->bed_count}}">
                                    </div>

                                    <div class="form-group">
                                        <label>نوع الغرفة</label>
                                        <input type="text" name="rooms[{{$index}}][room_type]" value="{{$room->room_type}}">
                                    </div>

                                    <div class="form-group">
                                        <label>حمام خاص؟</label>
                                        <input type="checkbox" class="checkbox"
                                               name="rooms[{{$index}}][has_bathroom]" value="1"
                                            {{ $room->has_bathroom ? 'checked':'' }}>
                                    </div>

                                    <button type="button" class="btn btn-danger remove-room">-</button>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <button type="button" class="btn btn-worn add-room">+</button>

                </div>
                <!-- ╚══════════════════════════════════════════╝ -->

            </div>

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">تحديث</button>
            </div>

        </form>
    </div>

    <script>
        let roomIndex = {{ count($unit->rooms) }};

        document.querySelector('.add-room').addEventListener('click', function () {

            let wrapper = document.getElementById('rooms-wrapper');
            let row = document.createElement('div');
            row.classList.add('room-row');

            row.innerHTML = `
            <div class="row-3">

                <div class="form-group">
                    <label>اسم الغرفة</label>
                    <input type="text" name="rooms[${roomIndex}][room_name]">
                </div>

                <div class="form-group">
                    <label>عدد الأسرة</label>
                    <input type="number" name="rooms[${roomIndex}][bed_count]" value="1">
                </div>

                <div class="form-group">
                    <label>نوع الغرفة</label>
                    <input type="text" name="rooms[${roomIndex}][room_type]">
                </div>

                <div class="form-group">
                    <label>حمام خاص؟</label>
                    <input type="checkbox" class="checkbox" name="rooms[${roomIndex}][has_bathroom]" value="1">
                </div>

                <button type="button" class="btn btn-danger remove-room">-</button>
            </div>
        `;

            row.querySelector('.remove-room').addEventListener('click', () => row.remove());

            wrapper.appendChild(row);
            roomIndex++;
        });

        document.querySelectorAll('.remove-room').forEach(btn => {
            btn.addEventListener('click', function () {
                btn.closest('.room-row').remove();
            });
        });
    </script>

</x-app-layout>
