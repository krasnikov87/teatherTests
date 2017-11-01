<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modExtra $modExtra */
/** @var pdoTools $pdoTools */

$outerTpl = $modx->getOption('outerTpl', $scriptProperties, 'tpl.teacherTest.outerTpl');

if (!$modExtra = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', $scriptProperties)
) {
    return 'Could not load modExtra class!';
}

//pdoTools
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
$path = $modx->getOption('pdofetch_class_path', null, MODX_CORE_PATH . 'components/pdotools/model/', true);
if($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoTools = new $pdoClass($modx, []);
}else{
    return 'false';
}

return $pdoTools->getChunk($outerTpl, []);