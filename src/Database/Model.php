<?php

namespace Jmarreros\Database;

use Jmarreros\Database\Drivers\DatabaseDriver;
use phpDocumentor\Reflection\Types\Static_;

abstract class Model {
	protected ?string $table = null;
	protected string $primaryKey = 'id';
	protected array $hidden = [];
	protected array $fillable = [];
	protected array $attributes = [];
	protected bool $insertTimeStamps = false;
	private static ?DatabaseDriver $driver = null;

	public static function setDatabaseDriver( DatabaseDriver $driver ) {
		self::$driver = $driver;
	}

	public function __construct() {
		if ( is_null( $this->table ) ) {
			$subclass    = new \ReflectionClass( static::class );
			$this->table = snake_case( $subclass->getShortName() . 's' );
		}
	}

	public function __set( $name, $value ) {
		$this->attributes[ $name ] = $value;
	}

	public function __get( $name ) {
		return $this->attributes[ $name ] ?? null;
	}

	public function save(): static {

		if ( $this->insertTimeStamps ) {
			$this->attributes['created_at'] = date( 'Y-m-d H:i:s' );
			$this->attributes['updated_at'] = date( 'Y-m-d H:i:s' );
		}

		$databaseColumns = implode( ',', array_keys( $this->attributes ) );
		$bind            = implode( ',', array_fill( 0, count( $this->attributes ), '?' ) );
		self::$driver->statement(
			"INSERT INTO $this->table ($databaseColumns) values ($bind)",
			array_values( $this->attributes )
		);

		return $this;
	}

	protected function setAttributes( array $attributes ): static {
		foreach ( $attributes as $key => $value ) {
			$this->__set( $key, $value );
		}

		return $this;
	}

	protected function massAssign( array $attributes ): static {

		if ( count( $this->fillable ) === 0 ) {
			throw new \Error( "Model " . static::class . " does not have fillable attributes" );
		}

		foreach ( $attributes as $key => $value ) {
			if ( in_array( $key, $this->fillable ) ) {
				$this->__set( $key, $value );
			}
		}

		return $this;
	}

	public function toArray(): array {
		return array_filter(
			$this->attributes,
			fn( $attr ) => ! in_array( $attr, $this->hidden )
		);
	}

	public static function create( array $attributes ): static {
		return ( new static() )->massAssign( $attributes )->save();
	}

	public static function first(): ?static {
		$model = new static();
		$rows  = self::$driver->statement( "SELECT * FROM $model->table ORDER BY $model->primaryKey LIMIT 1" );
		if ( count( $rows ) == 0 ) {
			return null;
		}

		return $model->setAttributes( $rows[0] );
	}

	public static function find( int|string $id ): ?static {
		$model = new static();
		$rows  = self::$driver->statement(
			"SELECT * FROM $model->table WHERE $model->primaryKey = ?",
			[ $id ] );

		if ( count( $rows ) == 0 ) {
			return null;
		}

		return $model->setAttributes( $rows[0] );
	}


	public static function all(): array {
		$model = new static();
		$rows  = self::$driver->statement( "SELECT * FROM $model->table" );

		if ( count( $rows ) == 0 ) {
			return [];
		}

		$models = [ $model->setAttributes( $rows[0] ) ];

		for ( $i = 1; $i < count( $rows ); $i ++ ) {
			$models[] = ( new static() )->setAttributes( $rows[ $i ] );
		}

		return $models;
	}

	public static function where( string $column, mixed $value ): array {
		$model = new static();
		$rows  = self::$driver->statement( "SELECT * FROM $model->table WHERE $column = ?",
			[ $value ] );

		if ( count( $rows ) == 0 ) {
			return [];
		}

		$models = [ $model->setAttributes( $rows[0] ) ];

		for ( $i = 1; $i < count( $rows ); $i ++ ) {
			$models[] = ( new static() )->setAttributes( $rows[ $i ] );
		}

		return $models;
	}

}