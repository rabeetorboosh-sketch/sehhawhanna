<link rel="stylesheet" href="{{asset('css/form.css')}}">

<form class="smart-form">
    <div class="row-2">
        <div class="form-group">
            <label>الاسم الكامل</label>
            <input type="text" placeholder="اكتب اسمك هنا">
        </div>
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" placeholder="name@example.com">
        </div>
    </div>

    <div class="row-3">
        <div class="form-group">
            <label>الجوال</label>
            <input type="tel" placeholder="05XXXXXXXX">
        </div>
        <div class="form-group">
            <label>المدينة</label>
            <select>
                <option disabled selected>اختر المدينة</option>
                <option>الرياض</option>
                <option>جدة</option>
                <option>الدمام</option>
            </select>
        </div>
    </div>
    <div class="row-3">
        <div class="form-group">
            <label>الرسالة</label>
            <textarea placeholder="اكتب رسالتك..."></textarea>
            <small>تقدر تكتب حتى 500 حرف.</small>
        </div>
    </div>


    <div class="actions">
        <button type="submit" class="btn-primary">primary</button>
        <button type="submit" class="btn-danger">danger</button>
        <button type="submit" class="btn-worn">worn</button>
        <button type="submit" class="btn-save">save</button>
        <button type="reset">إعادة تعيين</button>
    </div>
</form>
