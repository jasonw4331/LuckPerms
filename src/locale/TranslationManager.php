<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\locale;

use jasonwynn10\LuckPerms\LuckPerms;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Set;
use Webmozart\PathUtil\Path;
use function count;
use function is_dir;
use function mkdir;
use function str_ends_with;
use function strlen;
use function substr;

/**
 * @template T
 */
final class TranslationManager{
	/** The default locale used by LuckPerms messages */
	public const DEFAULT_LOCALE = 'en_US';

	/** @var Set<Locale> $installed */
	private Set $installed;
	private TranslationRegistry $registry;

	private string $translationsDirectory;
	private string $repositoryTranslationsDirectory;
	private string $customTranslationsDirectory;

	public function __construct(private LuckPerms $plugin){
		$this->installed = new Set(Locale::class, []);

		$this->translationsDirectory = Path::join($plugin->getDataFolder(), "translations");
		$this->repositoryTranslationsDirectory = Path::join($this->translationsDirectory, "repository");
		$this->customTranslationsDirectory = Path::join($this->translationsDirectory, "custom");

		if(!is_dir($this->repositoryTranslationsDirectory)){
			@mkdir($this->repositoryTranslationsDirectory);
		}
		if(!is_dir($this->customTranslationsDirectory)){
			@mkdir($this->customTranslationsDirectory);
		}
	}

	public function getTranslationsDirectory() : string{
		return $this->translationsDirectory;
	}

	public function getRepositoryTranslationsDirectory() : string{
		return $this->repositoryTranslationsDirectory;
	}

	public function getRepositoryStatusFile() : string{
		return Path::join($this->repositoryTranslationsDirectory, "status.json");
	}

	public function getInstalledLocales() : Set{
		return clone $this->installed;
	}

	public function reload() : void{
		// remove any previous registry
		if($this->registry !== null){
			GlobalTranslator::get()->removeSource($this->registry);
			$this->installed->clear();
		}

		// create a translation registry
		$this->registry = TranslationRegistry::create();
		$this->registry->defaultLocale(self::DEFAULT_LOCALE);

		// load custom translations first, then the base (built-in) translations after.
		$this->loadFromFileSystem($this->customTranslationsDirectory, false);
		$this->loadFromFileSystem($this->repositoryTranslationsDirectory, true);
		$this->loadFromResourceBundle();

		// register it to the global source, so our translations can be picked up by adventure-platform
		GlobalTranslator::translator()->addSource($this->registry);
	}

	private function loadFromResourceBundle() : void{
		$bundle = ResourceBundle::getBundle("luckperms", self::DEFAULT_LOCALE, Utf8ResourceBundleControl::get());
		try{
			$this->registry->registerAll(self::DEFAULT_LOCALE, $bundle, false);
		}catch(\InvalidArgumentException $e){
			$this->plugin->getLogger()->warning("Failed to load built-in translations: " . $e->getMessage());
		}
	}

	public function isTranslationFile(string $path) : bool{
		return str_ends_with($path, ".properties");
	}

	public function loadFromFileSystem(string $directory, bool $suppressDuplicatesError) : void{
		$translationFiles = [];
		/** @var \SplFileInfo $file */
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
			if($file->isFile() && $this->isTranslationFile($file->getFilename())){
				$translationFiles[] = $file->getPathname();
			}
		}

		if(count($translationFiles) === 0){
			return;
		}

		$loaded = new TypedMap('string', ResourceBundle::class);
		foreach($translationFiles as $translationFile){
			$result = $this->loadTranslationFile($translationFile);
			$loaded[$result->getKey()] = $result->getValue();
		}

		// try registering the locale without a country code - if we don't already have a registration for that
		foreach($loaded as $locale => $bundle){
			$localeWithoutCountry = new Locale($locale->getLanguage());
			if(!$locale->equals($localeWithoutCountry) && !$localeWithoutCountry->equals(self::DEFAULT_LOCALE) && $this->installed->add($localeWithoutCountry)){
				try{
					$this->registry->registerAll($localeWithoutCountry, $bundle, false);
				}catch(\InvalidArgumentException $e){
					// ignore
				}
			}
		}
	}

	private function loadTranslationFile(\SplFileInfo $translationFile) : TypedMap{
		$fileName = $translationFile->getFilename();
		$localeString = substr($fileName, 0, -strlen(".properties"));
		$locale = $this->parseLocale($localeString);

		if($locale === null){
			throw new \InvalidArgumentException("Invalid locale string: " . $localeString);
		}

		$bundle = null;
	}

}
