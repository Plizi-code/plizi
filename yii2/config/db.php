<?php

use yii\db\Connection;

$host = getenv('MYSQL_DATABASE') ? 'mysql8' : '127.0.0.1';
$database = getenv('MYSQL_DATABASE') ?: 'plizi';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('MYSQL_ROOT_PASSWORD') ?: 'root';

return [
    'class' => Connection::class,
    'dsn' => "mysql:host={$host};dbname={$database}",
    'username' => $user,
    'password' => $password,
    'charset' => 'utf8',
    'enableProfiling' => true,
    'enableLogging' => true,
    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 6000,
    'schemaCache' => 'cache',

    'enableQueryCache'=>true,
    'queryCacheDuration'=>200,
];
