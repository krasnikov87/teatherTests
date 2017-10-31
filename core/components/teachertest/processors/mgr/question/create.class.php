<?php

class teachersTestQuestionCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
    public $languageTopics = array('teacherstest');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $question = trim($this->getProperty('question'));
        if (empty($question)) {
            $this->modx->error->addField('question', $this->modx->lexicon('teachertest_question_err_question'));
        } elseif ($this->modx->getCount($this->classKey, array('question' => $question))) {
            $this->modx->error->addField('question', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'teachersTestQuestionCreateProcessor';