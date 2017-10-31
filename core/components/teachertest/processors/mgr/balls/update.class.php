<?php

class teacherTestBallsUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'teachersTestDiploma';
    public $classKey = 'teachersTestDiploma';
    public $languageTopics = array('teachertest');
    //public $permission = 'save';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $level = trim($this->getProperty('level'));
        $testId = trim($this->getProperty('test_id'));
        if(empty($testId)){
            return $this->modx->lexicon('teachertest_balls_err_save');
        }
        if (empty($id)) {
            return $this->modx->lexicon('teachertest_balls_err_ns');
        }

        if (empty($level)) {
            $this->modx->error->addField('level', $this->modx->lexicon('teachertest_item_test_err_level'));
        } elseif ($this->modx->getCount($this->classKey, array('level' => $level, 'id:!=' => $id, 'test_id'=> $testId))) {
            $this->modx->error->addField('level', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }

        return parent::beforeSet();
    }
}

return 'teacherTestBallsUpdateProcessor';
