<?php
$xpdo_meta_map['teacherTestAnswer']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_answer',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'question_id' => NULL,
    'answer' => NULL,
    'correct' => 0,
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
    'answer' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'correct' => 
    array (
      'dbtype' => 'TINYINT',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'teacher_test_answer_id_teacher_test_user_answer_answer_id' => 
    array (
      'class' => 'teachersTestUsersAnswer',
      'local' => 'id',
      'foreign' => 'answer_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'teacher_test_question_id_teacher_test_answer_question_id' => 
    array (
      'class' => 'teachersTestQuestion',
      'local' => 'question_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
