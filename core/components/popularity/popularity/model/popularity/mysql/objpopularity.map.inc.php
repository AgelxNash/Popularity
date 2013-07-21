<?php
$xpdo_meta_map['objPopularity']= array (
  'package' => 'popularity',
  'version' => '1.1',
  'table' => 'popularity',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'docid' => NULL,
    'comment' => 0,
    'view' => 0,
    'star' => 0,
    'days' => 0,
    'rang' => 0,
    'publishedon' => 0,
    'days_update' => 0,
  ),
  'fieldMeta' => 
  array (
    'docid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'unique',
    ),
    'comment' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
	  'default'=> 0,
    ),
    'view' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
	  'default'=> 0,
    ),
    'star' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
	  'default'=> 0,
    ),
    'days' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
	  'default'=> 0,
    ),
    'rang' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '20,5',
      'phptype' => 'float',
      'null' => false,
      'index' => 'index',
	  'default'=> 0,
    ),
    'publishedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'integer',
      'null' => false,
	   'phptype' => 'timestamp',
	   'default'=> 0,
      'index' => 'index',
    ),
    'days_update' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'integer',
	  'phptype' => 'timestamp',
	   'default'=> 0,
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'docid' => 
    array (
      'alias' => 'docid',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'docid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'rang' => 
    array (
      'alias' => 'rang',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'rang' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'pu' => 
    array (
      'alias' => 'pu',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'publishedon' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
        'days_update' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
);
