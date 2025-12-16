<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            إضافة استلام مهمة
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <div class="py-12">
        <form class="smart-form" action="{{ route('task_receipts.store') }}" method="post" enctype="multipart/form-data">
            @csrf

<input type="hidden" name="occurrence" value="{{$occurrence?? ''}}">
            <div class="row-2">
                <!-- اختيار الإسناد -->
                <div class="form-group">
                    <label>الإســــنـــــاد</label>
                    <select name="task_assignment_id" required>
                        <option value="">اختر الإسناد</option>
                        @foreach($assignments as $assignment)
                            <option value="{{ $assignment->id }}" {{($assignment->id ==$id ?'selected': '')}}>
                                {{ $assignment->task->controlUnit->name ??$assignment->task->user_control_unit?? $assignment->created_at  }} :  {{ $assignment->task->item->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>وقت الاستلام</label>
                    <input type="datetime-local" name="received_at" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_completed" value="1"  class="checkbox">
                        مــكــتـمـلـة
                    </label>
                </div>
                <div class="form-group" id="solution_method">
                    <label>    طريقة الحل </label>
                    <textarea name="solution_method"></textarea>
                </div>
            </div>


            <div class="row-2">
                <div class="form-group">
                    <label>الموقع</label>
                    <input type="text" name="location" placeholder="اكتب الموقع أو العنوان">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes"></textarea>
                </div>

            </div>
            <div class="row-2">
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="forwardCheckbox" name="forwarded_to_management" value="1" class="checkbox">
                        تحويل للادارة
                    </label>
                </div>

            </div>
            <div class="row-2">

                <div class="form-group" id="forwardReasonDiv" style="display:none;">
                    <label>ســبــب الـتـحـويـل</label>
                    <textarea name="forward_reason"></textarea>
                </div>
                <div class="row-2" id="multi-images">
                    <div class="form-group">
                        <input type="file" accept="image/*" name="images[]" id="image"   class="hidden">
                        <label for="image" class="btn btn-primary" ><i class="fas fa-camera"></i> إضافة صورة
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-additional" onclick="addNewImageField()">اضافة صورة</button>
                    </div>

                </div>


            </div>
<div class="row-3">
    <div class="form-group">
        <label for="file" class="btn btn-primary"><i class="fas fa-file"></i>اختر ملف </label>
        <input type="file" name="file-docs[]"  id="file" style="display: none">
    </div>
</div>




            <div class="actions">
                <button type="submit" class="btn-save">حفظ</button>
                <a href="{{ route('task_receipts.index') }}" class="btn btn-secondary">رجوع</a>
            </div>
        </form>
    </div>

    <script>
        const forwardCheckbox = document.getElementById('forwardCheckbox');
        const forwardReasonDiv = document.getElementById('forwardReasonDiv');

        function toggleForwardReason() {
            forwardReasonDiv.style.display = forwardCheckbox.checked ? 'flex' : 'none';
        }

        forwardCheckbox.addEventListener('change', toggleForwardReason);
        toggleForwardReason(); // عند تحميل الصفحة


        function addNewImageField() {
            let container = document.getElementById('multi-images');
            let inputId = 'images-extra' + Date.now();

            let newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = `images[]`;
            newInput.accept = 'image/*';
            newInput.id = inputId;
            newInput.classList.add('hidden');

            let newLabel = document.createElement('label');
            newLabel.setAttribute('for', inputId);
            newLabel.classList.add('btn') ;
            newLabel.classList.add('btn-primary') ;
            newLabel.innerHTML = '<i class="fas fa-camera"></i> إضافة صورة';

            container.appendChild(newInput);
            container.appendChild(newLabel);
        }
        try {
            var map;
            var marker;

            // Attempt to get user's current location with high accuracy
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;

                        // Initialize the map at user's location
                        map = L.map('map').setView([lat, lng], 16); // Zoom level set to 16 for better accuracy

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        // Add marker at current location
                        marker = L.marker([lat, lng]).addTo(map);

                        // Update input value (don't use textContent on <input>)
                        const locationInput = document.querySelector(".location");
                        if (locationInput) locationInput.value = `${lat}, ${lng}`;

                        map.on('click', onMapClick);
                    },
                    function (error) {
                        alert('Unable to retrieve your location. Please check permissions or try again.');
                        // Fallback map location if denied
                        map = L.map('map').setView([0, 0], 2);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);
                        map.on('click', onMapClick);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            function onMapClick(e) {
                try {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);

                    const locationInput = document.querySelector(".location");
                    if (locationInput) locationInput.value = `${e.latlng.lat}, ${e.latlng.lng}`;

                    document.querySelector('.map').style.visibility = "hidden";
                } catch (exception) {
                    alert('There is no internet');
                }
            }

        } catch (exception) {
            alert('There is no internet');
        }
    </script>
</x-app-layout>
