<?php
require_once "./vendor/autoload.php";

use Jmarreros\Database\Drivers\DatabaseDriver;
use Jmarreros\Database\Drivers\PdoDriver;
use Jmarreros\Database\Migrations\Migrator;

$driver = singleton(DatabaseDriver::class, PdoDriver::class);
$driver->connect( 'mysql', 'localhost', 3307, 'curso_framework', 'root', '' );

$migrator = new Migrator(
	__DIR__ . '/Database/migrations',
	__DIR__ . '/Templates',
	$driver
);

if ( $argv[1] == 'make:migration' ) {
	$migrator->make( $argv[2] );
} else if ( $argv[1] == 'migrate' ) {
	$migrator->migrate();
} else if ( $argv[1] == 'rollback' ) {
//	$migrator->rollback();
} else {
	echo "Invalid command";
}

