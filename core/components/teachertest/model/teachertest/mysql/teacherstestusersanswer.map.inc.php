<?php
$xpdo_meta_map['teachersTestUsersAnswer']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_user_answer',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'question_id' => NULL,
    'answer_ids' => NULL,
    'user_test_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'question_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
    'answer_ids' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
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
  'composites' => 
  array (
    'teacher_test_question_id_teacher_test_user_answer_question_id' => 
    array (
      'class' => 'teachersTestQuestion',
      'local' => 'question_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
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
