<?php

class teacherTestItemEnableProcessor extends modObjectProcessor
{
    public $objectType = 'teachersTestItem';
    public $classKey = 'teachersTestItem';
    public $languageTopics = array('teachertest');
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('teachertest_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var teacherTestItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('teachertest_item_err_nf'));
            }
            $countQuestions = $object->get('count_questions');
            $count =$this->modx->getCount('teachersTestQuestion', ['test_id'=> $id, 'status'=> 1]);
            if($count < $countQuestions){
                $this->modx->log(1, $this->modx->lexicon('teachertest_question_count_error', ['count'=>$countQuestions]));
                return $this->failure($this->modx->lexicon('teachertest_question_count_error', ['count'=>$countQuestions]));
            }

            $graduation = $this->modx->getCount('teachersTestDiploma', ['test_id'=> $id]);
            if($graduation < 1){
                return $this->failure($this->modx->lexicon('teachertest_graduation_count_error'));
            }
            $object->set('status', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'teacherTestItemEnableProcessor';
