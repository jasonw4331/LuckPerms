<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms;

use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;
use pocketmine\utils\Config;
use Ramsey\Collection\Map\AbstractTypedMap;
use function mb_strtolower;

class ConfigAdapter implements ConfigurationAdapter{

	private Config $configuration;

	public function __construct(private LuckPerms $luckPerms, private string $configPath){
		$this->reload();
	}

	public function reload() : void{
		$this->configuration = new Config($this->configPath, Config::YAML);
	}

	public function getString(string $path, ?string $def) : string{
		return $this->configuration->getNested($path, $def);
	}

	public function getLowercaseString(string $path, ?string $def) : string{
		return mb_strtolower($this->getString($path, $def));
	}

	public function getInteger(string $path, int $def) : int{
		return $this->configuration->getNested($path, $def);
	}

	public function getBoolean(string $path, bool $def) : bool{
		return $this->configuration->getNested($path, $def);
	}

	/**
	 * @inheritDoc
	 */
	public function getStringList(string $path, array $def) : array{
		return $this->configuration->getNested($path, $def);
	}

	/**
	 * @inheritDoc
	 */
	public function getStringMap(string $path, AbstractTypedMap $def) : AbstractTypedMap{
		return $this->configuration->getNested($path, $def);
	}

	public function getPlugin() : LuckPerms{
		return $this->luckPerms;
	}
}
