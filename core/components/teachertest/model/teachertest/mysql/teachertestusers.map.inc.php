<?php
$xpdo_meta_map['teacherTestUsers']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_users',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'test_id' => NULL,
    'user_id' => NULL,
    'order_id' => NULL,
    'product_id' => NULL,
    'results_id' => NULL,
    'diploma_id' => NULL,
    'educational_id' => NULL,
    'educational_name_album' => NULL,
    'educational_name_book' => NULL,
  ),
  'fieldMeta' => 
  array (
    'test_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'results_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'diploma_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'educational_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'educational_name_album' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'educational_name_book' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'composites' => 
  array (
    'teacher_test_item_id_test_user_test_id' => 
    array (
      'class' => 'teachersTestItem',
      'local' => 'test_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'aggregates' => 
  array (
    'teacher_test_users_id_teacher_test_user_answer_answer_id' => 
    array (
      'class' => 'teachersTestUsersAnswer',
      'local' => 'id',
      'foreign' => 'user_test_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
