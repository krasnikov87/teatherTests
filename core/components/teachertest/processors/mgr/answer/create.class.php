<?php

class teacherTestAnswerCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'teacherTestAnswer';
    public $classKey = 'teacherTestAnswer';
    public $languageTopics = array('teacherstest');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $answer = trim($this->getProperty('answer'));
        $questionId = trim($this->getProperty('question_id'));
        $correct = trim($this->getProperty('correct'));
        if(empty($questionId)){
            return $this->modx->lexicon('teachertest_answer_err_save');
        }
        if (empty($answer)) {
            $this->modx->error->addField('answer', $this->modx->lexicon('teachertest_balls_err_answer'));
        } elseif ($this->modx->getCount($this->classKey, array('answer' => $answer, 'question_id'=> $questionId))) {
            $this->modx->error->addField('answer', $this->modx->lexicon('teachertest_answer_err_ae'));
        }

        if($correct){
            $question = $this->modx->getObject('teachersTestQuestion', $questionId);
            $countCorrect = $this->modx->getCount($this->classKey, ['question_id'=>$questionId, 'correct'=>1]);
            if($question->get('type') == 'radio' && $countCorrect > 0){
              return  $this->modx->lexicon('teachertest_answer_one');
            }
        }


        return parent::beforeSet();
    }

}

return 'teacherTestAnswerCreateProcessor';