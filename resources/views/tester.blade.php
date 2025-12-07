<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $header  }}
        </h2>
    </x-slot>
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <div class="py-12">
        <div class="container flex">
            <!-- القائمة الجانبية بالحقول الجاهزة -->
            <div class="sidebar">

                <h3>الحقول المتاحة</h3>
                <div class="field-item" data-type="employee_name">اسم الموظف</div>
                <div class="field-item" data-type="job_title">الوظيفة</div>
                <div class="field-item" data-type="salary">الراتب</div>
                <div class="field-item" data-type="custom">حقل مخصص</div>
            </div>
            <input type="color" id="colorPicker">

            <button id="addTextBoxBtn">إضافة مربع نص</button>
            <div class="toolbar">
                <button onclick="formatText('bold')">Bold</button>
                <button onclick="formatText('italic')">Italic</button>
                <button onclick="formatText('underline')">Underline</button>

                <select onchange="setFontSize(this.value)">
                    <option value="12">12</option>
                    <option value="14" selected>14</option>
                    <option value="16">16</option>
                    <option value="20">20</option>
                </select>

                <input type="color" onchange="setFontColor(this.value)">
            </div>

            <!-- لوحة التصميم -->
            <div id="canvas" class="canvas">
                <p>اسحب الحقول هنا لتصميم القالب</p>
            </div>
        </div>

        <style>
            /* منع تحديد النص في كل الصفحة أثناء السحب */
            .field-item, .field-box {
                user-select: none;
            }


            .container { display: flex; gap: 20px; }
            .sidebar { width: 200px; border: 1px solid #ccc; padding: 10px; }
            .field-item { padding: 5px; margin: 5px 0; background: #eee; cursor: grab; }
            .canvas { flex: 1; height: 600px; border: 1px dashed #aaa; position: relative; }
            .field-box { position: absolute; padding: 5px; border: 1px solid #333; background: #fff; cursor: move; }
            .drag-clone {
                border: 1px dashed #555;
                padding: 5px;
                border-radius: 4px;
                transition: none !important;
            }
            .text-box {
                position: absolute;
                min-width: 120px;
                min-height: 40px;
                background: #fff;
                border: 1px solid #333;
                padding: 8px;
                border-radius: 4px;
                cursor: move;
                font-size: 14px;
                outline: none;
            }
            .toolbar {
                padding: 10px;
                margin-bottom: 10px;
                background: #eee;
                border: 1px solid #ccc;
            }
            .toolbar button {
                margin-right: 5px;
            }

        </style>
        <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
        <script>
            // اجعل الحقول الجانبية قابلة للسحب


            function dragMoveListener(event) {
                // لا حاجة لتحريك العنصر عند الإفلات، فقط ننسخه
            }

            // إضافة الحقل إلى اللوحة
            function addFieldToCanvas(type, x, y) {
                let canvas = document.getElementById('canvas');
                let fieldBox = document.createElement('div');
                fieldBox.className = 'field-box';
                fieldBox.style.left = x + 'px';
                fieldBox.style.top = y + 'px';
                fieldBox.textContent = type.replace('_', ' ');
                fieldBox.dataset.type = type;

                // اجعل الحقل قابل للسحب داخل اللوحة لتعديل موضعه
                interact(fieldBox).draggable({
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: 'parent',
                            endOnly: true
                        })
                    ],
                    onmove: function(event) {
                        let target = event.target;
                        let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                        let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
                        target.style.transform = `translate(${x}px, ${y}px)`;
                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);
                    }
                });

                canvas.appendChild(fieldBox);
            }

            function saveTemplate() {
                let fields = [];
                document.querySelectorAll('.field-box').forEach(box => {
                    let rect = box.getBoundingClientRect();
                    let canvasRect = document.getElementById('canvas').getBoundingClientRect();
                    fields.push({
                        type: box.dataset.type,
                        label: box.textContent,
                        x: rect.left - canvasRect.left,
                        y: rect.top - canvasRect.top,
                        width: rect.width,
                        height: rect.height
                    });
                });

                fetch('/templates/save', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({ fields: fields, name: 'قالب جديد' })
                }).then(res => alert('تم الحفظ!'));
            }
            interact('.field-item').draggable({
                inertia: true,
                // نستخدم autoScroll عشان تكون الحركة سلسة
                autoScroll: true,

                listeners: {
                    move(event) {
                        // إذا لم يكن هناك نسخة، أنشئ واحدة
                        if (!event.interaction.elementClone) {
                            let original = event.target;

                            // إنشاء نسخة
                            let clone = original.cloneNode(true);
                            clone.style.position = 'absolute';
                            clone.style.opacity = '0.7';
                            clone.style.background = '#ddd';
                            clone.style.pointerEvents = 'none';
                            clone.classList.add('drag-clone');

                            document.body.appendChild(clone);
                            event.interaction.elementClone = clone;

                            // تسجيل الإزاحة الأولية
                            event.interaction.startX = event.pageX;
                            event.interaction.startY = event.pageY;
                        }

                        let clone = event.interaction.elementClone;
                        let dx = event.pageX - event.interaction.startX;
                        let dy = event.pageY - event.interaction.startY;

                        let rect = event.target.getBoundingClientRect();

                        clone.style.left = rect.left + dx + 'px';
                        clone.style.top = rect.top + dy + 'px';
                    },

                    end(event) {
                        let clone = event.interaction.elementClone;
                        if (clone) {
                            let canvasRect = document.getElementById('canvas').getBoundingClientRect();

                            // إذا أُفلت داخل اللوحة
                            if (
                                clone.getBoundingClientRect().left > canvasRect.left &&
                                clone.getBoundingClientRect().right < canvasRect.right &&
                                clone.getBoundingClientRect().top > canvasRect.top &&
                                clone.getBoundingClientRect().bottom < canvasRect.bottom
                            ) {
                                addFieldToCanvas(
                                    event.target.dataset.type,
                                    clone.getBoundingClientRect().left - canvasRect.left,
                                    clone.getBoundingClientRect().top - canvasRect.top
                                );
                            }

                            clone.remove();
                            event.interaction.elementClone = null;
                        }
                    }
                }
            });
            document.getElementById('addTextBoxBtn').addEventListener('click', function () {
                addTextBoxToCanvas(100, 100);
            });
            function addTextBoxToCanvas(x, y) {
                let canvas = document.getElementById('canvas');

                let box = document.createElement('div');
                box.className = 'text-box';
                box.contentEditable = true; // يسمح للمستخدم بالكتابة
                box.textContent = "اكتب هنا...";
                box.style.left = x + 'px';
                box.style.top = y + 'px';

                canvas.appendChild(box);

                makeTextBoxDraggable(box);
                makeTextBoxResizable(box);
            }
            function makeTextBoxDraggable(el) {
                interact(el).draggable({
                    listeners: {
                        move(event) {
                            let x = (parseFloat(el.getAttribute('data-x')) || 0) + event.dx;
                            let y = (parseFloat(el.getAttribute('data-y')) || 0) + event.dy;

                            el.style.transform = `translate(${x}px, ${y}px)`;
                            el.setAttribute('data-x', x);
                            el.setAttribute('data-y', y);
                        }
                    }
                });
            }
            function makeTextBoxResizable(el) {
                interact(el).resizable({
                    edges: { left: true, right: true, bottom: true, top: true }
                })
                    .on('resizemove', function (event) {
                        let { x, y } = event.target.dataset;

                        x = (parseFloat(x) || 0) + event.deltaRect.left;
                        y = (parseFloat(y) || 0) + event.deltaRect.top;

                        Object.assign(event.target.style, {
                            width: `${event.rect.width}px`,
                            height: `${event.rect.height}px`,
                            transform: `translate(${x}px, ${y}px)`
                        });

                        event.target.dataset.x = x;
                        event.target.dataset.y = y;
                    });
            }
            function formatText(command) {
                document.execCommand(command, false, null);
            }

            function setFontSize(size) {
                document.execCommand("fontSize", false, "7");

                // تصحيح حجم الخط لأن execCommand تقدمه بأحجام قديمة
                let elements = document.getElementsByTagName("font");
                for (let el of elements) {
                    el.removeAttribute("size");
                    el.style.fontSize = size + "px";
                }
            }

            function setFontColor(color) {
                document.execCommand("foreColor", false, color);
            }
            var canvas = new fabric.Canvas('c');

            var textbox = new fabric.Textbox('اكتب هنا', {
                left: 100,
                top: 100,
                fontSize: 22,
                fill: '#000',
                fontFamily: 'Tahoma',
                editable: true,
            });

            canvas.add(textbox);
            canvas.setActiveObject(textbox);
            var obj = canvas.getActiveObject();
            var obj = canvas.getActiveObject();
            if (obj && obj.type === 'textbox') {
                obj.set('fill', 'red');
                canvas.renderAll();
            }
            obj.set('fontSize', 30);
            canvas.renderAll();
            obj.set('fontSize', 30);
            canvas.renderAll();
            obj.set('fontFamily', 'Cairo');
            canvas.renderAll();
            document.getElementById('colorPicker').oninput = function () {
                var obj = canvas.getActiveObject();
                if (obj && obj.type === 'textbox') {
                    obj.set('fill', this.value);
                    canvas.renderAll();
                }
            };

        </script>

    </div>
</x-app-layout>
