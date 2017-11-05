<?php

class teacherTest
{
    /** @var modX $modx */
    public $modx;

    public $testId;
    public $userId;
    public $orderId;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('teachertest_core_path', $config,
            $this->modx->getOption('core_path') . 'components/teachertest/'
        );
        $assetsUrl = $this->modx->getOption('teachertest_assets_url', $config,
            $this->modx->getOption('assets_url') . 'components/teachertest/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
        ), $config);

        $this->modx->addPackage('teachertest', $this->config['modelPath']);
        $this->modx->lexicon->load('teachertest:default');
    }

    public function getHash()
    {
        $hash = $this->userId . $this->testId. $this->orderId;
        return hash('md5', $hash);

    }

    public function getQuestion()
    {
        $hash = $this->getHash();

        $q = $this->modx->newQuery('teachersTestQuestion');
        $q->select($this->modx->getSelectColumns('teachersTestQuestion', 'teachersTestQuestion', '', ['id', 'question', 'type', 'test_id']));
        $q->where([
            'test_id'=> $this->testId,
            'status'=>1
        ]);
        if(!empty($_SESSION[$hash]['questions'])){
            $q->where(['id:NOT IN'=>$_SESSION[$hash]['questions']]);
        }
        $q->limit(1);
        $q->sortby('RAND()');
        $q->prepare();

        $q->stmt->execute();
        //print_r($q->toSQL());
        return $q->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkCorrect($questionId){

        $hash = $this->getHash();

        $correctIds = $this->getCorrect($questionId);

        $diff = array_diff($correctIds, $_SESSION[$hash]['answers'][$questionId]['ids']);
        if(count($diff)== 0){
            $diff = array_diff($_SESSION[$hash]['answers'][$questionId]['ids'], $correctIds);
        }
        if(count($diff)!= 0){
            $correct = 'false';
        }else{
            $correct = 'true';
        }

        $_SESSION[$hash]['answers'][$questionId]['correct'] = $correct;

        return $correct;
    }

    private function getCorrect($questionId){
        $q = $this->modx->newQuery('teacherTestAnswer');
        $q->select($this->modx->getSelectColumns('teacherTestAnswer', 'teacherTestAnswer', '', ['id']));
        $q->where(['question_id'=>$questionId, 'correct'=>1]);
        $q->prepare();
        $q->stmt->execute();
        $correctIds = [];
        foreach ($q->stmt->fetchAll(PDO::FETCH_ASSOC) as $cor){
            $correctIds[] =$cor['id'];
        }

        return $correctIds;
    }

    public function getAnswers($questionId, $chunk, $type)
    {
        $q = $this->modx->newQuery('teacherTestAnswer');
        $q->select($this->modx->getSelectColumns('teacherTestAnswer', 'teacherTestAnswer', '', ['id', 'answer']));
        $q->where(['question_id'=>$questionId]);
        $q->sortby('RAND()');
        $q->prepare();
        $q->stmt->execute();
        $answers ='';
        $idx = 1;
        foreach ($q->stmt->fetchAll(PDO::FETCH_ASSOC) as $answer){
            $answers .= $this->modx->getChunk($chunk, array_merge($answer, ['type' => $type, 'idx'=> $idx++]));
        }

        return $answers;
    }

    public function saveAnswer($data){
        $_SESSION[$this->getHash()]['answers'][$data['question']]=['ids'=>$data['answer']];
    }

    public function addQuestion($id){
        $_SESSION[$this->getHash()]['questions'][] = $id;
    }


}