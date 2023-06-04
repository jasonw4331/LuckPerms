<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\webeditor\store;

use jasonwynn10\LuckPerms\config\ConfigKeys;
use jasonwynn10\LuckPerms\CryptographyUtils;
use jasonwynn10\LuckPerms\LuckPerms;

class WebEditorStore{

	private WebEditorSessionMap $sessions;
	private WebEditorSocketMap $sockets;
	private WebEditorKeystore $keystore;

	public function __construct(LuckPerms $plugin) {
		$this->sessions = new WebEditorSessionMap();
		$this->sockets = new WebEditorSocketMap();
		$this->keystore = new WebEditorKeystore($plugin->getDataFolder().'editor-keystore.json');

		$keyPair = fn() => CompletableFuture::supplyAsync(
			[CryptographyUtils::class, 'generateKeyPair'],
			$plugin->getScheduler()
		);

		if($plugin->getConfiguration()->get(ConfigKeys::EDITOR_LAZILY_GENERATE_KEY())) {
			$this->keyPair = Suppliers::memoize($keyPair);
		}else{
			$future = $keyPair->get();
			$this->keyPair = fn() => $future;
		}
	}

	public function sessions() : WebEditorSessionMap{
		return $this->sessions;
	}

	public function sockets() : WebEditorSocketMap{
		return $this->sockets;
	}

	public function keystore() : WebEditorKeystore{
		return $this->keystore;
	}

	public function keyPair() : CompletableFuture{
		if(!$this->keyPair->get()->isDone()) {
			throw new \RuntimeException('Web editor keypair has not been generated yet! Has the server just started?');
		}
		return $this->keyPair->get()->join();
	}

}
