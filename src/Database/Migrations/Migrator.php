<?php

namespace Jmarreros\Database\Migrations;

use Jmarreros\Database\Drivers\DatabaseDriver;

class Migrator {

	public function __construct(
		private string $migrationDirectory,
		private string $templateDirectory,
		private DatabaseDriver $driver
	) {
		$this->migrationDirectory = $migrationDirectory;
		$this->templateDirectory  = $templateDirectory;
		$this->driver             = $driver;
	}

	public function make( string $migrationName ) {
		$migrationName = snake_case( $migrationName );

		$template = file_get_contents( $this->templateDirectory . "/migrations.php" );

		if ( preg_match( "/create_.*_table/", $migrationName ) ) {
			$table    = preg_replace_callback( "/create_(.*)_table/", fn( $match ) => $match[1], $migrationName );
			$template = str_replace( '$UP', "CREATE TABLE $table (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))", $template );
			$template = str_replace( '$DOWN', "DROP TABLE $table", $template );

		} elseif ( preg_match( "/.*(from|to)_(.*)_table/", $migrationName ) ) {
			$table    = preg_replace_callback( "/.*(from|to)_(.*)_table/", fn( $match ) => $match[2], $migrationName );
			$template = preg_replace( '/\$UP|\$DOWN/', "ALTER TABLE $table", $template );
		} else {
			$template = preg_replace_callback( "/DB::statement.*/", fn( $match ) => "// $match[0]", $template );
		}

		$date     = date( "Y_m_d_His" );
		$id       = uniqid();
		$filename = sprintf( "%s_%06d_%s.php", $date, $id, $migrationName );
		file_put_contents( $this->migrationDirectory . "/$filename", $template );

		return $filename;
	}

	private function log( string $message ) {
		print( $message . PHP_EOL );
	}

	private function createMigrationsTableIfNotExists() {
		$this->driver->statement( "CREATE TABLE IF NOT EXISTS migrations (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))" );
	}

	public function migrate() {
		$this->createMigrationsTableIfNotExists();
		$migrated   = $this->driver->statement( "SELECT * FROM migrations" );
		$migrations = glob( "$this->migrationDirectory/*.php" );

		if ( count( $migrated ) >= count($migrations) ) {
			$this->log( "No migrations to run" );
			return;
		}

		foreach ( array_slice( $migrations, count( $migrated ) ) as $file ) {
			$migration = require_once( $file );
			$migration->up();
			$name = basename( $file );
			$this->driver->statement( "INSERT INTO migrations (name) VALUES (?)", [ $name ] );
			$this->log( "Migrated: $name" );
		}

	}

}