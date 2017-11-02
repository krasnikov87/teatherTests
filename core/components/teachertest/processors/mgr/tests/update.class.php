<?php

class teacherTestItemUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'teachersTestItem';
    public $classKey = 'teachersTestItem';
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
        $name = trim($this->getProperty('name'));
        $countQuestions = $this->getProperty('count_questions');
        $status = $this->getProperty('status');
        if (empty($id)) {
            return $this->modx->lexicon('teachertest_item_test_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('teachertest_item_test_err_name'));
        } elseif ($this->modx->getCount($this->classKey, array('name' => $name, 'id:!=' => $id))) {
            $this->modx->error->addField('name', $this->modx->lexicon('teachertest_item_test_err_ae'));
        }


        if($status){
            $count =$this->modx->getCount('teachersTestQuestion', ['test_id'=> $id, 'status'=> 1]);
            if($count < $countQuestions){
                return $this->modx->lexicon('teachertest_question_count_error', ['count'=>$countQuestions]);
            }

            $graduation = $this->modx->getCount('teachersTestDiploma', ['test_id'=> $id]);
            if($graduation < 1){
                return $this->modx->lexicon('teachertest_graduation_count_error');
            }
        }

        return parent::beforeSet();
    }
}

return 'teacherTestItemUpdateProcessor';
