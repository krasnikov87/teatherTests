<?php
$xpdo_meta_map['teachersTestUsersAnswer']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_user_answer',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'question_id' => NULL,
    'answer_ids' => NULL,
    'user_test_id' => NULL,
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
    'correct' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
  ),
);
