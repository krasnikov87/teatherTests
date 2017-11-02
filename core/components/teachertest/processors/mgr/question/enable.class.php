<?php

class teachersTestQuestionEnableProcessor extends modObjectProcessor
{
    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
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
            /** @var teachersTestQuestion $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('teachertest_question_err_nf'));
            }
            if($this->modx->getCount('teacherTestAnswer', ['question_id'=> $id]) < 2){
                return $this->failure($this->modx->lexicon('teachertest_answer_count_error'));
            }
            $object->set('status', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'teachersTestQuestionEnableProcessor';
