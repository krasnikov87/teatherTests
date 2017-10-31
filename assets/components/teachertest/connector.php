<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var teacherTest $teacherTest */
$teacherTest = $modx->getService('teachertest', 'teacherTest', $modx->getOption('teachertest_core_path', null,
        $modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/'
);
$modx->lexicon->load('teachertest:default');

// handle request
$corePath = $modx->getOption('teachertest_core_path', null, $modx->getOption('core_path') . 'components/teachertest/');
$path = $modx->getOption('processorsPath', $teacherTest->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));