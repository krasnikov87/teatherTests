<?php

class teachersTestQuestionRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
    public $languageTopics = array('teachertest');
    //public $permission = 'remove';


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
            return $this->failure($this->modx->lexicon('teachertest_question_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var teachersTestQuestion $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('teachertest_question_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'teachersTestQuestionRemoveProcessor';