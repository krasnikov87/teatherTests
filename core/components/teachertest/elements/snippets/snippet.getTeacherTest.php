<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var teacherTest $teacherTest */



/*Проверяем авторизирован ли пользователь*/
if(!$modx->user->get('id')){
    $modx->sendRedirect($modx->makeUrl(1));
}

$outerTpl = $modx->getOption('outerTpl', $scriptProperties, 'tpl.teacherTest.outerTpl');
$answerTpl = $modx->getOption('answerTpl', $scriptProperties, 'tpl.teacherTest.answerTpl');
$testId = $modx->getOption('test', $scriptProperties, $modx->request->getParameters('test'));


if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load teacherTest class!';
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

$modx->regClientCSS($teacherTest->config['cssUrl'].'web/style.css');
$modx->regClientScript('http://brm.io/js/libs/matchHeight/jquery.matchHeight.js');
$modx->regClientScript($teacherTest->config['jsUrl'].'web/main.js');

return $modx->getChunk($outerTpl, array_merge($question, ['answers'=>$answers, 'test_name'=> $test->get('name'), 'order_id'=>'123']));