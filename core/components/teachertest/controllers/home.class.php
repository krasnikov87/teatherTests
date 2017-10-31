<?php

/**
 * The home manager controller for teacherTest.
 *
 */
class teacherTestHomeManagerController extends modExtraManagerController
{
    /** @var teacherTest $teacherTest */
    public $teacherTest;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('teachertest_core_path', null,
                $this->modx->getOption('core_path') . 'components/teachertest/') . 'model/teachertest/';
        $this->teacherTest = $this->modx->getService('teachertest', 'teacherTest', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('teachertest:default');
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('teachertest');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->teacherTest->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->teacherTest->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/teachertest.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/tests.grid.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/tests.windows.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/questions.grid.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/questions.windows.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/balls.grid.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/balls.windows.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->teacherTest->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        teacherTest.config = ' . json_encode($this->teacherTest->config) . ';
        teacherTest.config.connector_url = "' . $this->teacherTest->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "teachertest-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->teacherTest->config['templatesPath'] . 'home.tpl';
    }


    public function loadRichTextEditor()
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if ($useEditor && !empty($whichEditor)) {
            $textEditors = $this->modx->invokeEvent('OnRichTextEditorRegister');
            $this->setPlaceholder('text_editors',$textEditors);
            // invoke the OnRichTextEditorInit event
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', array(
                'editor' => $whichEditor, // Not necessary for Redactor
                'elements' => array('foo'), // Not necessary for Redactor
            ));
            if (is_array($onRichTextEditorInit)) {
                $onRichTextEditorInit = implode('', $onRichTextEditorInit);
            }
            $onRichTextEditorInit .= '<script>Tiny.convert_urls=false;</script>';
            $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        }
    }

    public function process(array $scriptProperties = array())
    {
        $this->loadRichTextEditor();
    }
}