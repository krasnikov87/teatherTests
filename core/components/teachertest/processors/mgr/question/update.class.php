<?php

class teachersTestQuestionUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
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
        $question = trim($this->getProperty('question'));
        $status = $this->getProperty('status');
        if (empty($id)) {
            return $this->modx->lexicon('teachertest_item_test_err_ns');
        }

        if (empty($question)) {
            $this->modx->error->addField('question', $this->modx->lexicon('teachertest_item_test_err_question'));
        } elseif ($this->modx->getCount($this->classKey, array('question' => $question, 'id:!=' => $id))) {
            $this->modx->error->addField('question', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }

        if($status){
            if($this->modx->getCount('teacherTestAnswer', ['question_id'=> $id]) < 2){
                return $this->modx->lexicon('teachertest_answer_count_error');
            }
        }

        return parent::beforeSet();
    }
}

return 'teachersTestQuestionUpdateProcessor';
