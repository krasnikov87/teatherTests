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

    $.ajax({
        method: 'POST',
        url: '/assets/components/teachertest/action.php',
        dataType: 'json',
        data: {
            action: 'web/getquestion',
            form: $(this).serialize()
        },
        success: function (data, textStatus, xhr) {
            console.log(textStatus);
            console.log(xhr);
            if(data.success) {
                var quest = '.numbers-question .qu-' + data.object.oldQuestion.number;
                var number = '.numbers-question .qu-' + data.object.number;
                $(quest).removeClass('active').addClass(data.object.oldQuestion.correct);
                $(number).addClass('active');
                $('.question-name span').html(data.object.number);
                $('[name=question]').val(data.object.question.id);
                $('[name=question-number]').val(data.object.number);
                $('.question-text').html(data.object.question.question);
                $('.answers .row').html(data.object.answers);
                $('.answer-number').matchHeight();
            }else{
                redirect(data.message);
            }
        }
    })
});

function redirect (url) {
    window.location.replace(testTeacher.url + url);
    // u.search = data.message;
    console.log(testTeacher.url + url)
}