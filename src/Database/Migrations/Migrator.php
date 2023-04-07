<?php

namespace Jmarreros\Database\Migrations;

class Migrator {

	public function __construct(
		private string $migrationDirectory,
		private string $templateDirectory
	) {
		$this->migrationDirectory = $migrationDirectory;
		$this->templateDirectory  = $templateDirectory;
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

}