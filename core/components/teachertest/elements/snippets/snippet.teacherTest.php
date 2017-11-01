<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var teacherTest $teacherTest */
/** @var pdoTools $pdoTools */

$allTpl = $modx->getOption('allTpl', $scriptProperties, 'tpl.teacherTest.allTest');

if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load teacherTest class!';
}

//pdoTools
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
$path = $modx->getOption('pdofetch_class_path', null, MODX_CORE_PATH . 'components/pdotools/model/', true);
if($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoTools = new $pdoClass($modx, []);
}else{
    return 'false';
}

$tests = $modx->getCollection('teachersTestItem', ['status'=>1]);
$output = '';
foreach ($tests as $test){
    $output .= $pdoTools->getChunk($allTpl, $test->toArray());
}
return $output;