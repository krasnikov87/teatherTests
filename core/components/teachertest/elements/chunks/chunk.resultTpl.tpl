<div class="row">
    <div class="col-lg-12">
        <h1>[[+test_name]]</h1>
    </div>
    <div class="col-lg-12 numbers-question">
        [[+count]]
    </div>
    <div class="col-lg-6">
        <h2>[[+congratulation]]</h2>
        <div><b>Результаты:</b></div>
        [[+correct:ne=`0`:and:ne=``:then=`<p class="small">Верных ответов: [[+correct]] </p>`:else=``]]
        [[+false:ne=`0`:and:ne=``:then=`<p class="small">Не верных ответов: [[+false]]</p>`:else=``]]
        <div><b>Ваш статус: [[+level]]</b></div>
        <div class="small">
            Для получения электронной версии сертификата произведите онлайн-оплату или оплату по квитанции.
        </div>
        <div class="button">
            <button class="btn btn-default hower">Онлайн-оплата</button>
            <button class="btn btn-default">Оплата по квитанции</button>
        </div>
        <div class="small">
            Вы всегда можете вернуться к оплате данного сертификата позже, в разделе Мои заявки
        </div>
    </div>


</div>