$(function() {
    $('.answer-number').matchHeight();
});

$('body').on('submit', '.answers-form', function (event) {
    event.preventDefault();
    var answers = $('.answer-number input');
    var error = true;
    for(var i = 0; i < answers.length; i++){;
        if(answers.eq(i).prop('checked')){
            error = false
        }
    }

    if(error) return false;
    var res = send({
        action:'answer',
        form: $(this).serialize()
    });
    var data = JSON.parse(res);
    if(data.success) {
        var quest = '.numbers-question .qu-' + data.oldQuestion.number;
        var number = '.numbers-question .qu-' + data.number;
        $(quest).removeClass('active').addClass(data.oldQuestion.correct);
        $(number).addClass('active');
        $('.question-name span').html(data.number);
        $('[name=question]').val(data.question.id);
        $('[name=question-number]').val(data.number);
        $('.question-text').html(data.question.question);
        $('.answers .row').html(data.answers);
        $('.answer-number').matchHeight();
    }else{
        var res = send({
            action: data.action,
            form: $.param(data.form),
        });
        $('.allTest').html(res);
    }
});

$('body').on('click', '.testStart', function (event) {
   event.preventDefault();
   var res = send({
       action: 'new',
       testid: $(this).data('test'),
   });
   $('.allTest').html(res);
});


function send(data){

     return $.ajax({
        method: 'POST',
        url: testTeacher.url,
        dataType: 'json',
         async: false,
        data: data,
    }).responseText;

};