<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\locale;

class TranslationRegistry{
	private string $name;
	private array $translations = [];
	private string $defaultLocale = TranslationManager::DEFAULT_LOCALE;

	public function __construct(string $name){
		$this->name = $name;
	}

}
