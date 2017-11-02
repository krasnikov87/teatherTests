<?php

class teacherTestBallsGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'teachersTestDiploma';
    public $classKey = 'teachersTestDiploma';
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
        $test_id = $this->getProperty('test_id');
        $c->where([
            'test_id' => $test_id
        ]);
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
            'title' => $this->modx->lexicon('teachertest_balls_update'),
            //'multiple' => $this->modx->lexicon('modextra_items_update'),
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        );

        return $array;
    }

}

return 'teacherTestBallsGetListProcessor';