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

            $object->set('status', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'teacherTestItemEnableProcessor';
