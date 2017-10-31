<?php

class teacherTestQuestionGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'teachersTestQuestion';
    public $classKey = 'teachersTestQuestion';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where(array(
                'name:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ));
        }

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = array();

        // Edit
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('teachertest_test_question_update'),
            //'multiple' => $this->modx->lexicon('modextra_items_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );

        if (!$array['status']) {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('teachertest_test_question_enable'),
                'multiple' => $this->modx->lexicon('teachertest_test_questions_enable'),
                'action' => 'enableItem',
                'button' => true,
                'menu' => true,
            );
        } else {
            $array['actions'][] = array(
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('teachertest_test_question_disable'),
                'multiple' => $this->modx->lexicon('teachertest_test_questions_disable'),
                'action' => 'disableItem',
                'button' => true,
                'menu' => true,
            );
        }

        // Remove
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('teachertest_test_question_remove'),
            'multiple' => $this->modx->lexicon('teachertest_questions_remove'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        );

        return $array;
    }

}

return 'teacherTestQuestionGetListProcessor';