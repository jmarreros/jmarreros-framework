<?php
require_once "./vendor/autoload.php";

use Jmarreros\Database\Migrations\Migrator;

$migrator = new Migrator(
	__DIR__ . '/Database/migrations',
	__DIR__ . '/Templates');

if ( $argv[1] == 'make:migration'){
	$migrator->make($argv[2]);
}

