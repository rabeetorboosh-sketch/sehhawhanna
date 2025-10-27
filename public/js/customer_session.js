document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcodeInput');
    const clientSelect = document.getElementById('clientSelect');
    const startCameraBtn = document.getElementById('startCamera');
    const qrReaderContainer = document.getElementById('qr-reader');
    const scannerStatus = document.getElementById('scannerStatus');

    // ✅ تعريف clientsearch لأنه مستخدم في handleBarcodeInput
    const clientsearch = document.querySelector('.client-search');

    let scannerActive = false;
    let html5QrcodeScanner = null;

    function findClientByBarcode(barcode) {
        const options = clientSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].getAttribute('data-barcode') === barcode) {
                return options[i];
            }
        }
        return null;
    }

    function handleBarcodeInput(code) {
        const clientOption = findClientByBarcode(code);

        if (clientOption) {
            clientSelect.value = clientOption.value;
            scannerStatus.innerHTML = '<span class="success">✓ تم التعرف على العميل: ' + clientOption.text + '</span>';
            clientsearch.dispatchEvent(new Event('change'));
            setTimeout(() => { scannerStatus.innerHTML = ''; }, 2000);
        } else {
            scannerStatus.innerHTML = '<span class="error">✗ لم يتم التعرف على العميل</span>';
            setTimeout(() => { scannerStatus.innerHTML = ''; }, 5000);
        }

        barcodeInput.value = '';
    }

    barcodeInput.addEventListener('change', function() {
        if (this.value.trim() !== '') {
            handleBarcodeInput(this.value.trim());
        }
    });

    startCameraBtn.addEventListener('click', function() {
        if (!scannerActive) {
            startQRCodeScanner();
        } else {
            stopQRCodeScanner();
        }
    });

    function startQRCodeScanner() {
        qrReaderContainer.style.display = 'block';
        scannerStatus.innerHTML = '<span class="info">جارٍ المسح... ضع رمز الـQR أمام الكاميرا</span>';
        startCameraBtn.innerHTML = '<i class="fa-solid fa-stop"></i>';
        startCameraBtn.classList.add('active-scanner');

        const qrRegionId = "qr-reader";

        if (html5QrcodeScanner) {
            try { html5QrcodeScanner.stop().catch(()=>{}); } catch(e){}
            html5QrcodeScanner = null;
        }

        html5QrcodeScanner = new Html5Qrcode(qrRegionId);

        const config = { fps: 10, qrbox: { width: 300, height: 300 } };

        Html5Qrcode.getCameras().then(cameras => {
            const cameraId = (cameras && cameras.length) ? cameras[0].id : null;
            html5QrcodeScanner.start(
                cameraId,
                config,
                (decodedText) => {
                    handleBarcodeInput(decodedText);
                    stopQRCodeScanner();
                }
            ).then(() => {
                scannerActive = true;
            }).catch(err => {
                console.error("خطأ في بدء ماسح الـQR:", err);
                scannerStatus.innerHTML = '<span class="error">تعذّر تشغيل الماسح</span>';
                startCameraBtn.innerHTML = '<i class="fa-solid fa-camera"></i>';
            });
        }).catch(() => {
            scannerStatus.innerHTML = '<span class="error">لم يتم العثور على كاميرا أو تم رفض الإذن</span>';
            startCameraBtn.innerHTML = '<i class="fa-solid fa-camera"></i>';
        });
    }

    function stopQRCodeScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
            }).catch(()=>{});
            html5QrcodeScanner = null;
        }

        qrReaderContainer.style.display = 'none';
        scannerActive = false;
        startCameraBtn.innerHTML = '<i class="fa-solid fa-camera"></i>';
        startCameraBtn.classList.remove('active-scanner');
        scannerStatus.innerHTML = '';
    }

    window.addEventListener('beforeunload', function() {
        if (html5QrcodeScanner) {
            try { html5QrcodeScanner.stop().catch(()=>{}); } catch(e){}
        }
    });
});
