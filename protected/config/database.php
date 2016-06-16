<?php

// This is the database connection configuration.
return array(
//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
    // uncomment the following lines to use a MySQL database
    /*
      'connectionString' => 'mysql:host=localhost;dbname=testdrive',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => '',
      'charset' => 'utf8',
     */
    'connectionString' => 'mysql:host=127.0.0.1:3306;dbname=iweb44',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'tablePrefix' => 'iwebshop_',
    //显示每个sql语句与运行的时间
    'enableProfiling' => true,
);
