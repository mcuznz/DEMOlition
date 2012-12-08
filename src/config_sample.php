<?php
// Connect to Database using Doctrine DBAL. MySQL, SQLite, PostgreSQL, OCI8 and MSSQL are available.
// See http://docs.doctrine-project.org/projects/doctrine-dbal/en/2.0.x/reference/configuration.html
$db_config = array(
	'driver'	=>	'pdo_mysql',
	'host'		=>	'localhost',
	'user'		=>	'your_db_user',
	'password'	=>	'your_db_pass',
	'dbname'	=>	'your_db_name'
);

// The name of table where usage statistics should be stored
define ('STAT_TABLE', 'stats');
