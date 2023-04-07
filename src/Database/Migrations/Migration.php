<?php

namespace Jmarreros\Database\Migrations;

interface Migration {
	public function up();
	public function down();
}