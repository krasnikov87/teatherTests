<?php
$xpdo_meta_map['teachersTestUsersAnswer']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_user_answer',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'answer_id' => NULL,
    'user_test_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'answer_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'user_test_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'teacher_test_answer_id_teacher_test_user_answer_answer_id' => 
    array (
      'class' => 'teacherTestAnswer',
      'local' => 'answer_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'teacher_test_users_id_teacher_test_user_answer_answer_id' => 
    array (
      'class' => 'teacherTestUsers',
      'local' => 'user_test_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
