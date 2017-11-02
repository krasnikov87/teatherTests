<?php
$xpdo_meta_map['teachersTestItem']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_item',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
    'image' => NULL,
    'status' => 1,
    'finished_count' => 0,
    'payment_count' => 0,
    'count_questions' => 15,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '512',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'image' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'status' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
    'finished_count' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'payment_count' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'count_questions' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'intefer',
      'null' => false,
      'default' => 15,
    ),
  ),
  'aggregates' => 
  array (
    'teacher_test_item_id_teacher_test_diploma_test_id' => 
    array (
      'class' => 'teachersTestDiplomas',
      'local' => 'id',
      'foreign' => 'test_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'teacher_test_item_id_test_user_test_id' => 
    array (
      'class' => 'teachersTestUsers',
      'local' => 'id',
      'foreign' => 'test_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'teacher_test_item_id_teacher_test_question_test_id' => 
    array (
      'class' => 'teachersTestQuestion',
      'local' => 'id',
      'foreign' => 'test_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
