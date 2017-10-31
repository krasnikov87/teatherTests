<?php

class teachersTestItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'teachersTestItem';
    public $classKey = 'teachersTestItem';
    public $languageTopics = array('teacherstest');
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('teachertest_item_test_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
            $this->modx->error->addField('name', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'teachersTestItemCreateProcessor';