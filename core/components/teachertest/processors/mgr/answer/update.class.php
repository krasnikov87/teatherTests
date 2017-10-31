<?php

class teacherTestAnswerUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'teacherTestAnswer';
    public $classKey = 'teacherTestAnswer';
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
        $answer = trim($this->getProperty('answer'));
        $questionId = trim($this->getProperty('question_id'));
        $correct = trim($this->getProperty('correct'));

        if(empty($questionId)){
            return $this->modx->lexicon('teachertest_balls_err_save');
        }
        if (empty($id)) {
            return $this->modx->lexicon('teachertest_balls_err_ns');
        }

        if (empty($answer)) {
            $this->modx->error->addField('answer', $this->modx->lexicon('teachertest_item_test_err_answer'));
        } elseif ($this->modx->getCount($this->classKey, array('answer' => $answer, 'id:!=' => $id, 'question_id'=> $questionId))) {
            $this->modx->error->addField('answer', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }

        if($correct){
            $question = $this->modx->getObject('teachersTestQuestion', $questionId);
            $countCorrect = $this->modx->getCount($this->classKey, ['question_id'=>$questionId, 'correct'=>1, 'id:!='=>$id]);
            if($question->get('type') == 'radio' && $countCorrect > 0){
                return  $this->modx->lexicon('teachertest_answer_one');
            }
        }

        return parent::beforeSet();
    }
}

return 'teacherTestAnswerUpdateProcessor';
