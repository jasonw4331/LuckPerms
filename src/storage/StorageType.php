<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\storage;

use jasonwynn10\LuckPerms\LuckPerms;
use pocketmine\utils\EnumTrait;

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
			new self("POSTGRESQL", "SQLite", "sqlite"),
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