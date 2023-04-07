<?php

use Jmarreros\Database\DB;
use Jmarreros\Database\Migrations\Migration;

return new class() implements Migration {

	public function up() {
		DB::statement('CREATE TABLE user_meta (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))');
	}

	public function down() {
		DB::statement('DROP TABLE user_meta');
	}
};