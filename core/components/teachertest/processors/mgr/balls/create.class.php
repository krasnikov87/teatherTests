<?php

class teacherTestBallsCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'teachersTestDiploma';
    public $classKey = 'teachersTestDiploma';
    public $languageTopics = array('teacherstest');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $level = trim($this->getProperty('level'));
        $testId = trim($this->getProperty('test_id'));
        if(empty($testId)){
            return $this->modx->lexicon('teachertest_balls_err_save');
        }
        if (empty($level)) {
            $this->modx->error->addField('level', $this->modx->lexicon('teachertest_balls_err_level'));
        } elseif ($this->modx->getCount($this->classKey, array('level' => $level, 'test_id'=> $testId))) {
            $this->modx->error->addField('level', $this->modx->lexicon('teachertest_balls_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'teacherTestBallsCreateProcessor';