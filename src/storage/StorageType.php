<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\storage;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static StorageType CUSTOM()
 * @method static StorageType JSON()
 * @method static StorageType JSON_COMBINED()
 * @method static StorageType MARIADB()
 * @method static StorageType MONGODB()
 * @method static StorageType MYSQL()
 * @method static StorageType POSTGRESQL()
 * @method static StorageType SQLITE()
 * @method static StorageType TOML()
 * @method static StorageType TOML_COMBINED()
 * @method static StorageType YAML()
 * @method static StorageType YAML_COMBINED()
 */
final class StorageType{
	use EnumTrait {
		__construct as Enum___construct;
	}

	private array $identifiers;

	protected static function setup() : void{
		self::registerAll(
			new self("YAML", "YAML", "yaml", "yml"),
			new self("JSON", "JSON", "json", "flatfile"),
			new self("TOML", "TOML", "toml"),
			new self("YAML_COMBINED", "YAML Combined", "yaml-combined"),
			new self("JSON_COMBINED", "json-combined"),
			new self("TOML_COMBINED", "toml-combined"),
			new self("MONGODB", "MongoDB", "mongodb"),
			new self("MARIADB", "MariaDB", "mariadb"),
			new self("MYSQL", "MySQL", "mysql"),
			new self("POSTGRESQL", "PostgreSQL", "postgresql"),
			new self("SQLITE", "SQLite", "sqlite"),
			new self("CUSTOM", "Custom", "custom"),
		);
	}

	private function __construct(string $key, string ...$identifiers){
		$this->Enum___construct($key);
		$this->identifiers = $identifiers;
	}

	public static function parse(string $name, StorageType $default) : StorageType {
		foreach(self::getAll() as $t) {
			foreach($t->getIdentifiers() as $id) {
				if(strtolower($name) === strtolower($id))
					return $t;
			}
		}
		return $default;
	}

	/**
	 * @return string[]
	 */
	public function getIdentifiers() : array {
		return $this->identifiers;
	}
}