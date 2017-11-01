<link rel="stylesheet" href="/assets/components/teachertest/css/web/style.css">
<div class="row">
    <div class="col-lg-12">
        <h1>[[+test_name]]</h1>
    </div>
    <div class="col-lg-12 numbers-question">
        <span class="qu-1 true">1</span>
        <span class="qu-2 false">2</span>
        <span class="qu-3 false">3</span>
        <span class="qu-4 false">4</span>
        <span class="qu-5 false">5</span>
        <span class="qu-6 true">6</span>
        <span class="qu-7 true">7</span>
        <span class="qu-8 true">8</span>
        <span class="qu-9 false">9</span>
        <span class="qu-10 active">10</span>
        <span class="qu-11 ">11</span>
        <span class="qu-12">13</span>
        <span class="qu-12">14</span>
        <span class="qu-12">15</span>
        <span class="qu-12">16</span>
        <span class="qu-12">17</span>
        <span class="qu-12">18</span>
        <span class="qu-12">19</span>
        <span class="qu-12">20</span>
    </div>
    <form class="answers-form" method="post">
        <input type="hidden" name="test" value="[[+test_id]]">
        <input type="hidden" name="question" value="[[+id]]">
        <input type="hidden" name="question-number" value="1">
        <div class="col-lg-12 question-name"><h2>Вопрос № <span>1</span></h2></div>
        <div class="col-lg-12 question-text">
            [[+question]]
        </div>
        <div class="col-lg-10 answers">
            <div class="row">
                [[+answers]]
        </div>
        <div class="col-lg-12">
            <div class="row">
                <button class="btn btn-default pull-left" type="submit">Далее</button>
                <button class="btn btn-default pull-right">Отменить прохождение</button>
            </div>
        </div>
    </form>
</div>