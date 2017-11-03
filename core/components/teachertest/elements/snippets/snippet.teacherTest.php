<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var teacherTest $teacherTest */


$allTpl = $modx->getOption('allTpl', $scriptProperties, 'tpl.teacherTest.allTest');
$outerTpl = $modx->getOption('outerTpl', $scriptProperties, 'tpl.teacherTest.outerTpl');
$answerTpl = $modx->getOption('answerTpl', $scriptProperties, 'tpl.teacherTest.answerTpl');
$testId = $modx->getOption('test', $scriptProperties, $modx->request->getParameters('test'));
$countsTpl = $modx->getOption('countsTpl', $scriptProperties, 'tpl.teacherTest.countsTpl');
$resultTpl = $modx->getOption('resultTpl', $scriptProperties, 'tpl.teacherTest.resultTpl');

if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load teacherTest class!';
}


$status = $modx->request->getParameters('status');

switch ($status){
    case 'test':
        /*Проверяем авторизирован ли пользователь*/
        if(!$modx->user->get('id')){
            $modx->sendRedirect($modx->makeUrl(1)); //todo
        }


        /*Получение теста*/
        $test = $modx->getObject('teachersTestItem', $testId);

        $q = $modx->newQuery('teachersTestQuestion', ['test_id'=>$testId, 'status'=>1]);
        $q->select($modx->getSelectColumns('teachersTestQuestion', 'teachersTestQuestion', '', ['id', 'question', 'type', 'test_id']));
        $q->limit(1);
        $q->sortby('RAND()');
        $q->prepare();

        $q->stmt->execute();
        $question = $q->stmt->fetch(PDO::FETCH_ASSOC);

        /*Ответы*/
        $a = $modx->newQuery('teacherTestAnswer', ['question_id' => $question['id']]);
        $a->select($modx->getSelectColumns('teacherTestAnswer', 'teacherTestAnswer', '', ['id', 'answer']));
        $a->sortby('RAND()');
        $a->prepare();
        $a->stmt->execute();
        $answers = '';
        $idx = 1;
        foreach ($a->stmt->fetchAll(PDO::FETCH_ASSOC) as $answer){
            $answers .= $modx->getChunk($answerTpl, array_merge($answer, ['type' => $question['type'], 'idx'=> $idx++]));
        }

        /*Список вопросов*/
        $counts = '';
        for($i = 1; $i<= $test->get('count_questions'); $i++){
            $counts .= $modx->getChunk($countsTpl, ['i'=>$i]);
        }

        $hash = $modx->user->id.$test->get('id').'123';
        $_SESSION[hash('md5', $hash)] = [
            'answerChunk'=> $answerTpl,
            'order_id'=>123,
            'test_id'=>$test->get('id'),
            'questions'=> [
                $question['id']
            ],
            'answers' => [],
        ];


        $output =  $modx->getChunk($outerTpl, array_merge($question, [
            'answers'=>$answers,
            'test_name'=> $test->get('name'),
            'order_id'=>'123',
            'count'=> $counts
        ]));

        break;

    case 'result':

        $orderId = $modx->request->getParameters('orderId');
        $test = $modx->getObject('teachersTestItem', $testId);

        $hash = $modx->user->id.$test->get('id').'123'; //todo
        $hash = hash('md5', $hash);

        if(!$_SESSION[$hash] /*|| count($_SESSION[$hash]['answers']) != $test->get('count_questions')*/){
            $modx->sendRedirect($modx->makeUrl($modx->resource->id));
        }

        $counts = '';
        $i = 1;

        foreach ($_SESSION[$hash]['answers'] as $answer){
            $counts .= $modx->getChunk($countsTpl, [
                'class' => $answer['correct'],
                'i'=>$i++,
            ]);
            $countCorrect += $answer['correct'] == 'true' ? 1 : 0;
            $countFalse += $answer['correct'] == 'false' ? 1 : 0;
        }


        $q = $modx->newQuery('teachersTestDiploma');
        $q->select($modx->getSelectColumns('teachersTestDiploma', 'teachersTestDiploma', '', ['level', 'id']));
        $q->where([
            'min_balls:<='=>$countCorrect,
            'test_id'=>$testId,
            ]);
        $q->sortby('teachersTestDiploma.min_balls', 'DESC');
        $q->limit(1);
        $q->prepare();
        $q->stmt->execute();
        $level = $q->stmt->fetch(PDO::FETCH_ASSOC);


        if(!$modx->getCount('teacherTestUsers', [
            'test_id' => $testId,
            'user_id'=> $modx->user->id,
            'order_id'=> $orderId,
            ])) {
            /*save*/
            $saveTest = $modx->newObject('teacherTestUsers', [
                'test_id' => $testId,
                'user_id' => $modx->user->id,
                'order_id' => $orderId,
                'results' => $countCorrect,
                'diploma_id' => isset($level['id']) ? $level['id'] : 0,
                'educational_name_album' => '',
                'educational_name_book' => '',
                'educational_id' => 0,
            ]);


            $saveTest->save();


            /*save answers*/
            foreach ($_SESSION[$hash]['answers'] as $question => $answer) {
                $saveAnswer = $modx->newObject('teachersTestUsersAnswer');
                $saveAnswer->set('user_test_id', $saveTest->get('id'));
                $saveAnswer->set('question_id', $question);
                $saveAnswer->set('answer_ids',json_encode($answer['ids']));
                $saveAnswer->set('correct', $answer['correct'] == 'true' ? 1: 0);
                $saveAnswer->save();
            }

            $test->set('finished_count', $test->get('finished_count')+1);
            $test->save();
        }

        $congratulation = !empty($level) ?
            $modx->lexicon('teachertest_res_header_success') :
            $modx->lexicon('teachertest_res_header_fail');


        $level = isset($level['level']) && $level['level'] == 0 ? $modx->lexicon('teachertest_res_status_participant') :
            isset($level['level']) && $level['level'] > 0 ? $modx->lexicon('teachertest_res_status_level', ['level' =>$level['level']]): '';





        $output = $modx->getChunk($resultTpl, [
            'count'=> $counts,
            'test_name' => $test->get('name'),
            'congratulation'=> $congratulation,
            'correct'=> $countCorrect,
            'false'=>$countFalse,
            'level'=>$level,
            'order'=>$orderId
        ]);

        $modx->regClientHTMLBlock(
            "<script>$(document).ready($.ajax({
                    method: 'POST',
                    url: '/assets/components/teachertest/action.php',
                    dataType: 'json',
                    data: {
                        action: 'clear',
                        hash: '".$hash."'
                
                    }
                  })
                  );</script>"
        );

        break;
    case 'canceled':
        $output = '<h1>[[*pagetitle]]</h1>';
    default:
        $tests = $modx->getCollection('teachersTestItem', ['status'=>1]);
        if(!$output) $output = '<h1>[[*pagetitle]]</h1>';
        foreach ($tests as $test){
            $output .= $modx->getChunk($allTpl, $test->toArray());
        }
        break;
}

$modx->regClientCSS($teacherTest->config['cssUrl'].'web/style.css');
$modx->regClientScript('http://brm.io/js/libs/matchHeight/jquery.matchHeight.js');
$modx->regClientScript($teacherTest->config['jsUrl'].'web/main.js');
$modx->regClientStartupHTMLBlock('<script>
var testTeacher = {
    url: "'.$modx->makeUrl($modx->resource->id, '', '', 'full').'"
};
</script>');

return $output;