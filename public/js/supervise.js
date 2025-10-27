delayRezoneLabel = document.querySelector('.delay-rezone-label');
delayRezoneInput = document.querySelector('.delay-rezone-input');
delayClick = document.querySelector('.delay-click');

transRezoneLabel = document.querySelector('.trans-rezone-label');
transRezoneInput = document.querySelector('.trans-rezone-input');
transClick = document.querySelector('.trans-click');
phone = document.getElementById('phone');
employee_id = document.getElementById('employee_id');
let toggeler =false;
if (delayClick){

     toggeler = delayClick.checked;
}
try {
    changeLate();
    if(delayClick){

        delayClick.onchange = function () {
            changeLate();
        }

    }
    function changeLate(){
        if (toggeler ) {
            delayRezoneLabel.style.maxHeight = '0';
            delayRezoneInput.style.maxHeight = '0';
            delayRezoneLabel.style.padding = '0';

            delayRezoneInput.value = '';
            delayRezoneInput.style.borderWidth = '0';
            toggeler = false;

        } else {
            delayRezoneLabel.style.maxHeight = '100px';
            delayRezoneInput.style.maxHeight = '100px';
            delayRezoneInput.style.borderWidth = '1px';
            toggeler = true;

        }
    }

}catch (e){

}

let transtoggeler = true;

if (transClick){

    transClick.onchange = function () {
        if (!transtoggeler) {
            transRezoneLabel.style.maxHeight = '0';
            transRezoneInput.style.maxHeight = '0';
            transRezoneLabel.style.padding = '0';
            transRezoneInput.style.padding = '0';
            transRezoneInput.value = '';
            transtoggeler = true;

        } else {
            transRezoneLabel.style.maxHeight = '100px';
            transRezoneInput.style.maxHeight = '100px';

            transtoggeler = false;

        }
    }
}




const clientsearch = document.querySelector('.client-search');
const input = document.querySelector('.searcher');
const select = document.querySelector('#clientSelect');
const originalOptions = Array.from(select.options);

if (input){

    input.addEventListener('input', function () {
        const keyword = input.value.toLowerCase();

        select.value='';
        clientsearch.style.maxHeight='300px';
        clientsearch.style.border='1px inset';


        select.innerHTML = '';
        originalOptions .forEach(option => {
            if (option.text.toLowerCase().includes(keyword)) {
                select.appendChild(option);
            }
        });

        if (select.options.length === 0) {
            const emptyOption = document.createElement('option');
            emptyOption.text = 'لا يوجد نتائج';
            emptyOption.disabled = true;
            select.appendChild(emptyOption);
        }

    });
}


if(clientsearch){

    clientsearch.addEventListener('change', function () {
        // نقل النص إلى الـ input
        const selectedOption = clientsearch.options[select.selectedIndex];
        input.value = selectedOption.text;
        select.style.maxHeight = '0';
        select.style.padding = '0';
        select.style.border = 'none';


        if(phone){

            phone.value=selectedOption.dataset.phone;
        }
        if(employee_id){

            employee_id.value=selectedOption.dataset.emp;
        }
    });
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



function addImageInput() {
    const container = document.getElementById('image-inputs');


    // إنشاء label جديد
    const label = document.createElement('label');
    label.classList.add('btn');
    label.innerHTML = '<i class="fa-solid fa-camera"></i>';

    // إنشاء input جديد
    const newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.name = 'images[]';
    newInput.accept = 'image/*';
    newInput.multiple = true;
    newInput.setAttribute('capture', 'environment');
    newInput.style.display = 'none';

    // عند الضغط على label يفتح الـ input
    label.onclick = () => newInput.click();

    // تجميعهم داخل الـ div
    container.appendChild(label);
    container.appendChild(newInput);

    // إضافتهم قبل زر الإضافة

}
