<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\locale;

use jasonwynn10\LuckPerms\LuckPerms;
use pocketmine\lang\Language;

class TranslationManager{
	public static string $DEFAULT_LOCALE = Language::FALLBACK_LANGUAGE;

	private LuckPerms $plugin;
	private string $translationsDirectory;
	private array $installed = [];
	private $registry;

	public function __construct(LuckPerms $plugin) {
		$this->plugin = $plugin;
		$this->translationsDirectory = $plugin->getDataFolder().'translations'.DIRECTORY_SEPARATOR;
	}

	public function getTranslationsDirectory() : string {
		return $this->translationsDirectory;
	}

	public function getInstalledLocales() : array {
		return $this->installed;
	}

	public function reload() : void {
		if($this->registry !== null) {
			$this->installed = [];
		}
		$this->registry = TranslationRegistry::create();
		$this->registry->defaultLocale(self::$DEFAULT_LOCALE);
	}

	private function loadBase() : void {
		$bundle = new \ResourceBundle(self::$DEFAULT_LOCALE, $this->translationsDirectory);
		$this->registry->registerAll();
	}

	public function loadCustom() : void {
		//
	}

}