<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var teacherTest $teacherTest */


$allTpl = $modx->getOption('allTpl', $scriptProperties, 'tpl.teacherTest.allTest');
$outerTpl = $modx->getOption('outerTpl', $scriptProperties, 'tpl.teacherTest.outerTpl');
$answerTpl = $modx->getOption('answerTpl', $scriptProperties, 'tpl.teacherTest.answerTpl');
$countsTpl = $modx->getOption('countsTpl', $scriptProperties, 'tpl.teacherTest.countsTpl');
$resultTpl = $modx->getOption('resultTpl', $scriptProperties, 'tpl.teacherTest.resultTpl');

if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load teacherTest class!';
}

$action = $modx->getOption('action', $scriptProperties, '');

//todo $testId
//todo $orderId
$data = [];
parse_str($modx->getOption('data', $scriptProperties, ''), $data);

$teacherTest->testId = $modx->getOption('testId', $scriptProperties, $data['test']);
$teacherTest->orderId =  isset($data['order'])? $data['order']: 0;
$teacherTest->userId = $modx->user->id;


switch ($action){
    case 'new':
        /*Проверяем авторизирован ли пользователь*/
        if(!$teacherTest->userId){
            $modx->sendRedirect($modx->makeUrl(1)); //todo
        }

        $hash = $teacherTest->getHash();
        if($_SESSION[$hash]) unset($_SESSION[$hash]);
        
        /*Получение теста*/
        $test = $modx->getObject('teachersTestItem', $teacherTest->testId);

        $question = $teacherTest->getQuestion();

        /*Ответы*/
        $answers = $teacherTest->getAnswers($question['id'], $answerTpl, $question['type']);

        /*Список вопросов*/
        $counts = '';
        for($i = 1; $i<= $test->get('count_questions'); $i++){
            $counts .= $modx->getChunk($countsTpl, ['i'=>$i, 'class'=> '']);
        }


        $_SESSION[$hash] = [
            'order_id'=>$teacherTest->orderId,
            'test_id'=>$test->get('id'),
            'questions'=> [
                $question['id']
            ],
            'answers' => [],
        ];

        
        $output =  $modx->getChunk($outerTpl, array_merge($question, [
            'answers'=>$answers,
            'test_name'=> $test->get('name'),
            'order_id'=>$teacherTest->orderId,
            'count'=> $counts
        ]));
        
        break;

    case 'answer' :
        $hash =$teacherTest->getHash();

        if($_SESSION[$hash]){
            $teacherTest->saveAnswer($data);
        }else{
           return $modx->toJSON(['action'=>'error','success'=>false]);
        }

        $correct = $teacherTest->checkCorrect($data['question']);

        $test = $modx->getObject('teachersTestItem', $data['test']);
        if($data['question-number'] == $test->get('count_questions')){
            return $modx->toJSON([
                'action'=>'result',
                'success'=>false,
                'form'=>[
                    'test'=>$teacherTest->testId,
                    'order'=>$teacherTest->orderId,
                    ]
                ]);
        }

        $question = $teacherTest->getQuestion();
        if(empty($question)){
            return $modx->toJSON(['action'=>'error','success'=>false]);
        }
        $teacherTest->addQuestion($question['id']);


        $answers = $teacherTest->getAnswers($question['id'], $answerTpl, $question['type']);

        $output = [
            'success'=>true,
            'question'=>$question,
            'answers' =>  $answers,
            'oldQuestion'=>[
                'number' => $data['question-number'],
                'correct' => $correct,
            ],
            'number'=> ++$data['question-number'],
        ];

        return $modx->toJSON($output);
        break;

    case 'result':


        $test = $modx->getObject('teachersTestItem', $teacherTest->testId);


        $hash = $teacherTest->getHash();
      
        if(!$_SESSION[$hash] || count($_SESSION[$hash]['answers']) != $test->get('count_questions')){
            return $modx->toJSON(['action'=>'', 'success'=>'false']);
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
            'test_id'=>$teacherTest->testId,
        ]);
        $q->sortby('teachersTestDiploma.min_balls', 'DESC');
        $q->limit(1);
        $q->prepare();
        $q->stmt->execute();
        $level = $q->stmt->fetch(PDO::FETCH_ASSOC);


        if($modx->getCount('teacherTestUsers', [
            'test_id' => $teacherTest->testId,
            'user_id'=> $teacherTest->userId,
            'order_id'=> $teacherTest->orderId,
        ])== 0) {
            /*save*/
            $saveTest = $modx->newObject('teacherTestUsers', [
                'test_id' => $teacherTest->testId,
                'user_id' => $teacherTest->userId,
                'order_id' => $teacherTest->orderId,
                'product_id'=>1, /*TODO*/
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

       //return print_r($level,1);

        if ($level['level'] == 4){
            $levelnew = $modx->lexicon('teachertest_res_status_participant');
        } elseif ($level['level'] < 4 && !empty($level['level'])){
            $levelnew = $modx->lexicon('teachertest_res_status_level', ['level' =>$level['level']]);
        }else{
            $levelnew = '';
        }






        $output = $modx->getChunk($resultTpl, [
            'count'=> $counts,
            'test_name' => $test->get('name'),
            'congratulation'=> $congratulation,
            'correct'=> $countCorrect,
            'false'=>$countFalse,
            'level'=>$levelnew,
            'order'=>$teacherTest->orderId
        ]);

        break;

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
    url: "/assets/components/teachertest/action.php"
};
</script>');

return $output;