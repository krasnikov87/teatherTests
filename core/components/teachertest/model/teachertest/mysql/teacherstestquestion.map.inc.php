<?php
$xpdo_meta_map['teachersTestQuestion']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_question',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'test_id' => NULL,
    'question' => NULL,
    'status' => 1,
    'type' => 'radio',
  ),
  'fieldMeta' => 
  array (
    'test_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'null' => false,
    ),
    'question' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'status' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => 'radio',
    ),
  ),
  'composites' => 
  array (
    'teacher_test_item_id_teacher_test_question_test_id' => 
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
    'teacher_test_question_id_teacher_test_answer_question_id' => 
    array (
      'class' => 'teacherTestAnswer',
      'local' => 'id',
      'foreign' => 'question_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'teacher_test_question_id_teacher_test_user_answer_question_id' => 
    array (
      'class' => 'teachersTestUsersAnswer',
      'local' => 'id',
      'foreign' => 'question_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
