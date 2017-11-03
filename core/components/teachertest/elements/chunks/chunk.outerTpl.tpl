<div class="row">
    <div class="col-lg-12">
        <h1>[[+test_name]]</h1>
    </div>
    <div class="col-lg-12 numbers-question">
        [[+count]]
    </div>
    <form class="answers-form" method="post">
        <input type="hidden" name="test" value="[[+test_id]]">
        <input type="hidden" name="order" value="[[+order_id]]">
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
        </div>
        <div class="col-lg-12">
            <div class="row">
                <button class="btn btn-default pull-left" type="submit">Далее</button>
                <a href="[[~[[*id]]]]" class="btn btn-default pull-right">Отменить прохождение</a>
            </div>
        </div>
    </form>
</div>