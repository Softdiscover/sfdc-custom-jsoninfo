<?php
/**
 * PHPUnit bootstrap file
 *
 */
define( 'ABSPATH', true );
define( 'DB_HOST', 'nowhere' );
define( 'DB_NAME', 'none' );
define( 'DB_USER', 'nobody' );
define( 'DB_PASSWORD', 'nothing' );

require_once __DIR__ . '/../vendor/autoload.php';
 
// Since our plugin files are loaded with composer, we should be good to go