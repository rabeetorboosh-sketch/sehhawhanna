<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة وحدة سكنية
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{asset('css/form.css')}}">

    <div class="py-12">
        @if(session('success'))
            <div id="success-alert" class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="smart-form" action="{{route('housing_units.store')}}" method="post">
            @csrf

            <div class="row-2">

                <div class="form-group">
                    <label>كود الوحدة</label>
                    <input name="unit_code" type="text" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>اسم الوحدة</label>
                    <input name="name" type="text" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>نوع الوحدة</label>
                    <select name="unit_type">
                        <option disabled selected>اختر النوع</option>
                        <option value="شقة">شقة</option>
                        <option value="فيلا">فيلا</option>
                        <option value="عمارة">عمارة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>عدد المطابخ</label>
                    <input name="total_kitchens" type="number" min="0">
                </div>

                <div class="form-group">
                    <label>عدد الحمامات</label>
                    <input name="total_bathrooms" type="number" min="0">
                </div>

                <div class="form-group">
                    <label>العنوان</label>
                    <input name="address" type="text">
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes"></textarea>
                </div>


            </div>
            <!-- ╔═══════════════ غرف الوحدة ═══════════════╗ -->
            <div class="form-group" style="border: 1px solid gray; border-radius: 10px; padding: 5px">
                <h3>الغرف</h3>

                <div id="rooms-wrapper">
                    <div class="room-row">
                        <div class="grid-tbl-5">

                            <div class="form-group">
                                <label>اسم الغرفة</label>
                                <input name="rooms[0][room_name]" data-name="room_name">
                            </div>

                            <div class="form-group">
                                <label>عدد الأسرة</label>
                                <input type="number" name="rooms[0][bed_count]" data-name="bed_count" value="1">
                            </div>

                            <div class="form-group">
                                <label>نوع الغرفة</label>
                                <input name="rooms[0][room_type]" data-name="room_type">
                            </div>

                            <div class="form-group">
                                <label>حمام خاص؟</label>
                                <input class="checkbox" type="checkbox" name="rooms[0][has_bathroom]" data-name="has_bathroom" value="1">
                            </div>

                            <button type="button" class="btn btn-danger remove-room">-</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-worn add-room">+</button>
            </div>
            <!-- ╚══════════════════════════════════════════╝ -->

            <div class="actions">
                <button type="reset" class="btn-primary">إعادة تعيين</button>
                <button type="submit" class="btn-save">حفظ</button>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/form.js') }}"></script>

    <script>
        let roomIndex = 1;

        document.querySelector('.add-room').addEventListener('click', function () {
            let wrapper = document.getElementById('rooms-wrapper');

            // إنشاء الصف من الصفر
            let row = document.createElement('div');
            row.classList.add('room-row');

            row.innerHTML = `
            <div class="grid-tbl-5">

                <div class="form-group">
                    <label>اسم الغرفة</label>
                    <input type="text" name="rooms[${roomIndex}][room_name]" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>عدد الأسرة</label>
                    <input type="number" name="rooms[${roomIndex}][bed_count]" min="1" value="1">
                </div>

                <div class="form-group">
                    <label>نوع الغرفة</label>
                    <input type="text" name="rooms[${roomIndex}][room_type]" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>حمام خاص؟</label>
                    <input type="checkbox" class="checkbox" name="rooms[${roomIndex}][has_bathroom]" value="1">
                </div>

                <button type="button" class="btn btn-danger remove-room">-</button>
            </div>
        `;

            // زر الحذف
            row.querySelector('.remove-room').addEventListener('click', function () {
                row.remove();
            });

            // إضافة الصف الجديد
            wrapper.appendChild(row);

            roomIndex++;
        });

        // تفعيل حذف الصف الأول الافتراضي
        document.querySelectorAll('.remove-room').forEach(btn => {
            btn.addEventListener('click', function () {
                btn.closest('.room-row').remove();
            });
        });
    </script>


</x-app-layout>
