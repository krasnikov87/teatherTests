<?php
$xpdo_meta_map['teachersTestQuestion']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_question',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
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
);
