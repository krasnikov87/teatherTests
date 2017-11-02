<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var teacherTest $teacherTest */


$allTpl = $modx->getOption('allTpl', $scriptProperties, 'tpl.teacherTest.allTest');

if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load teacherTest class!';
}

$tests = $modx->getCollection('teachersTestItem', ['status'=>1]);
$output = '';
foreach ($tests as $test){
    $output .= $modx->getChunk($allTpl, $test->toArray());
}
return $output;