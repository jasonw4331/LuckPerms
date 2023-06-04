<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\http;

use pocketmine\utils\Internet;
use pocketmine\utils\InternetException;
use pocketmine\utils\InternetRequestResult;
use function json_decode;
use function str_ends_with;
use function var_dump;
use const CURLOPT_ENCODING;
use const CURLOPT_POST;
use const JSON_OBJECT_AS_ARRAY;
use const JSON_THROW_ON_ERROR;

class BytebinClient extends AbstractHttpClient{

	private string $url;

	public function __construct(string $url, private string $userAgent){
		$this->url = str_ends_with($url, "/") ? $url : $url . '/';
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function getUserAgent() : string{
		return $this->userAgent;
	}

	public function makeHttpRequest(string $url) : InternetRequestResult {
		return parent::makeHttpRequest($url);
	}

	/**
	 * POSTs GZIP compressed content to bytebin.
	 *
	 * @param string      $buffer         the compressed content
	 * @param string      $contentType    the type of the content
	 * @param string|null $extraUserAgent extra string to append to the user agent
	 * @return Content the key of the resultant content
	 */
	public function postContent(string $buffer, string $contentType, ?string $extraUserAgent = null) : Content {
		$userAgent = $this->userAgent . ($extraUserAgent !== null ? "/$extraUserAgent" : "");
		$response = Internet::simpleCurl($this->url . 'post', 10, [$userAgent], [
			CURLOPT_POST => 1,
			CURLOPT_ENCODING => 'gzip'
		]);

		var_dump($response->getHeaders()); // TODO
		if(!isset($response->getHeaders()['Location'])) {
			throw new InternetException('Key not returned');
		}
		$key = $response->getHeaders()['Location'];
		return new Content($key);
	}

	/**
	 * GETs json content from bytebin
	 *
	 * @param string $id the id of the content
	 *
	 * @return mixed[] the data
	 * @throws \JsonException
	 */
	public function getJsonContent(string $id) : array {
		$response = Internet::simpleCurl($this->url . $id, 10, [$this->userAgent]);
		return json_decode($response->getBody(), true, flags: JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);
	}
}
