<?php

if (empty($_REQUEST['action'])) {
    die('Access denied');
}


define('MODX_API_MODE', true);
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');


if($_REQUEST['action'] == 'clear' || $_REQUEST['hash']){
    unset($_SESSION[$_REQUEST['hash']]);
    return true;
}


if (!empty($_REQUEST['pageId'])) {
    if ($resource = $modx->getObject('modResource', (int)$_REQUEST['pageId'])) {
        if ($resource->get('context_key') != 'web') {
            $modx->switchContext($resource->get('context_key'));
        }
        $modx->resource = $resource;
    }
}

if (!$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/', [])
) {
    return 'Could not load teacherTest class!';
}

$data = [];
parse_str($_REQUEST['form'], $data);

$output = $modx->runProcessor($_REQUEST['action'],$data,array('processors_path' => MODX_CORE_PATH.'components/teachertest/processors/'));
echo $modx->toJSON($output->response);