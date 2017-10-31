<?php
$xpdo_meta_map['teachersTestDiploma']= array (
  'package' => 'teachertest',
  'version' => '1.1',
  'table' => 'teacher_test_diploma',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'test_id' => NULL,
    'min_balls' => NULL,
  ),
  'fieldMeta' => 
  array (
    'test_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'null' => false,
    ),
    'min_balls' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'teacher_test_item_id_teacher_test_diploma_test_id' => 
    array (
      'class' => 'teachersTestItem',
      'local' => 'test_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
