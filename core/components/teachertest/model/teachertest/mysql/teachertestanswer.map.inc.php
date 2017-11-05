<?php
$xpdo_meta_map['teacherTestAnswer']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_answer',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
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
);
