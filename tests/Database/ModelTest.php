<?php

namespace Jmarreros\Test\Database;

use Jmarreros\Database\Drivers\DatabaseDriver;
use Jmarreros\Database\Drivers\PdoDriver;
use Jmarreros\Database\Model;
use PHPUnit\Framework\TestCase;

class Mock_model extends Model {
	protected bool $insertTimeStamps = true;
}

class ModelTest extends  TestCase {
	protected ?DatabaseDriver $driver = null;

	protected function setUp(): void {
		if (is_null($this->driver)) {
			$this->driver = new PdoDriver();
			Model::setDatabaseDriver($this->driver);
			try {
				$this->driver->connect('mysql', 'localhost', 3307, 'curso_framework_test', 'root', '');
			} catch (\Exception $e) {
				$this->markTestSkipped("Can't connect to Database: " . $e->getMessage());
			}
		}
	}

	protected function tearDown(): void {
		$this->driver->statement("DROP DATABASE IF EXISTS curso_framework_test");
		$this->driver->statement("CREATE DATABASE curso_framework_test");
	}

	private function createTestTable($name, $columns, $withTimeStamps = false ) {
		$sql = "CREATE TABLE $name ( id INT AUTO_INCREMENT PRIMARY KEY, "
			. implode( ',', array_map(fn($c) => "$c VARCHAR(256)", $columns) );

		if ($withTimeStamps) {
			$sql .= ", created_at DATETIME, updated_at DATETIME NULL";
		}

		$sql .= ")";
		$this->driver->statement($sql);
	}

	public function test_save_basic_model_with_attributes(){
		$this->createTestTable('user', ['name', 'email'], true);
		$model = new Mock_model();
		$model->name = 'John';
		$model->email = 'admin@admin.com';
		$model->save();

		$rows = $this->driver->statement("SELECT * FROM user");

		$expected = [
			"id" => 1,
			"name" => "John",
			"email" => "admin@admin.com",
			"created_at" => date('Y-m-d H:i:s'),
			"updated_at" => null
		];

		$this->assertEquals($expected, $rows[0]);
		$this->assertCount( 1, $rows );
	}

}