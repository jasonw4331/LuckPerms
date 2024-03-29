<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\http;

use pocketmine\utils\Internet;
use pocketmine\utils\InternetRequestResult;

abstract class AbstractHttpClient{

	protected function makeHttpRequest(string $url) : InternetRequestResult{
		return Internet::getURL($url);
	}

}
