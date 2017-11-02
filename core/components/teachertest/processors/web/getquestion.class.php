<?php
class getQuestionProcessor extends modObjectGetProcessor{

    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
    public $languageTopics = array('teacherstest');

    public function initialize() {

        $testId = $this->getProperty('test');
        $hash =$this->getHash();

        if($_SESSION[$hash]){
           $this->saveAnswer();
        }else{
            return $this->failure($this->modx->lexicon('teacherstest_error_session'));
        }


        $q = $this->modx->newQuery($this->classKey);
        $q->select($this->modx->getSelectColumns($this->classKey, $this->classKey, '', ['id', 'question', 'type']));
        $q->where(['id:NOT IN'=>$_SESSION[$hash]['questions'], 'status'=>1, 'test_id'=> $testId]);
        $q->sortby('RAND()');
        $q->limit(1);
        $q->prepare();
        $q->stmt->execute();

        $this->object = $this->modx->getObject($this->classKey,$q);
        return true;
    }

    public function cleanup() {
        $number = $this->getProperty('question-number');
        $correct = $this->checkCorrect();
        if(!is_object($this->object)){
            return $this->failure($this->modx->makeUrl(26));
        }
        $this->addQuestion();

        return $this->success('',[
            'question'=>$this->object->toArray(),
            'answers' =>  $this->getAnswers(),
            'oldQuestion'=>[
                'number' => $number,
                'correct' => $correct,
            ],
            'number'=> ++$number,
        ]);
    }

    private function getHash(){
        $testId = $this->getProperty('test');
        $order = $this->getProperty('order');

        $user  = $this->modx->user->id;
        $hash = $user . $testId . $order;
        return hash('md5', $hash);

    }

    private function checkCorrect(){

        $hash = $this->getHash();
        $correctIds = $this->getCorrect();
        $questionId = $this->getProperty('question');

        $diff = array_diff($correctIds, $_SESSION[$hash]['answers'][$questionId]['ids']);
        $this->modx->log(1, print_r($diff,1));
        if(count($diff)== 0){
            $diff = array_diff($_SESSION[$hash]['answers'][$questionId]['ids'], $correctIds);
        }
        $this->modx->log(1, print_r($diff,1));
        if(count($diff)!= 0){
            $correct = 'false';
        }else{
            $correct = 'true';
        }

        $_SESSION[$hash]['answers'][$questionId]['correct'] = $correct;

        return $correct;
    }

    private function getCorrect(){
        $q = $this->modx->newQuery('teacherTestAnswer');
        $q->select($this->modx->getSelectColumns('teacherTestAnswer', 'teacherTestAnswer', '', ['id']));
        $q->where(['question_id'=>$this->getProperty('question'), 'correct'=>1]);
        $q->prepare();
        $q->stmt->execute();
        $correctIds = [];
        foreach ($q->stmt->fetchAll(PDO::FETCH_ASSOC) as $cor){
            $correctIds[] =$cor['id'];
        }

        return $correctIds;
    }

    private function getAnswers(){
        $q = $this->modx->newQuery('teacherTestAnswer');
        $q->select($this->modx->getSelectColumns('teacherTestAnswer', 'teacherTestAnswer', '', ['id', 'answer']));
        $q->where(['question_id'=>$this->object->get('id')]);
        $q->prepare();
        $q->stmt->execute();
        $answers ='';
        $idx = 1;
        foreach ($q->stmt->fetchAll(PDO::FETCH_ASSOC) as $answer){
            $answers .= $this->modx->getChunk($this->getChunk(), array_merge($answer, ['type' => $this->object->get('type'), 'idx'=> $idx++]));
        }

        return $answers;
    }

    private function getChunk(){
       return $_SESSION[$this->getHash()]['answerChunk'];
    }

    private function saveAnswer(){
        $_SESSION[$this->getHash()]['answers'][$this->getProperty('question')]=['ids'=>$this->getProperty('answer')];
    }

    private function addQuestion(){
        $_SESSION[$this->getHash()]['questions'][] = $this->object->get(id);
    }

}

return 'getQuestionProcessor';