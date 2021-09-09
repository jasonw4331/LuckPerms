<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\dependencies;

use jasonwynn10\LuckPerms\dependencies\classloader\IsolatedClassLoader;
use jasonwynn10\LuckPerms\dependencies\relocation\RelocationHandler;
use jasonwynn10\LuckPerms\LuckPerms;
use jasonwynn10\LuckPerms\util\MoreFiles;
use pocketmine\scheduler\ClosureTask;

class DependencyManager{
	private LuckPerms $plugin;
	private DependencyRegistry $registry;
	private string $cacheDirectory;

	private array $loaded = [];
	private array $loaders = [];
	private ?RelocationHandler $relocationHandler = null;

	public function __constrtuct(LuckPerms $plugin) {
		$this->plugin = $plugin;
		$this->registry = new DependencyRegistry($plugin);
		$this->cacheDirectory = self::setupCacheDirectory($plugin);
	}

	private function getRelocationHandler() : RelocationHandler {
		if($this->relocationHandler === null) {
			$this->relocationHandler = new RelocationHandler($this);
		}
		return $this->relocationHandler;
	}

	public function obtainClassLoaderWith(array $dependencies) : IsolatedClassLoader {
		$set = $dependencies;
		foreach($dependencies as $dependency) {
			if($this->loaded[$dependency->getName()] === null) {
				throw new \Exception('Dependency '.$dependency.' is not loaded');
			}
		}

		$classLoader = $this->loaders[$dependency]; // TODO: change to php logic
		if($classLoader !== null) {
			return $classLoader;
		}

		$urls = array_map(function(string $file) {
			// TODO: map file name to URL (requires bytebin)
		}, $this->loaded);
	}

	public function loadStorageDependencies(array $storageTypes) : void {
		$this->loadDependencies($this->registry->resolveStorageDependencies($storageTypes));
	}

	public function loadDependencies(array $dependencies) : void {
		$latch = new CountDownLatch(count($dependencies));

		foreach($dependencies as $dependency) {
			$this->plugin->getScheduler()->scheduleTask(new ClosureTask(function() use ($latch, $dependency) {
				try{
					$this->loadDependency($dependency);
				}catch(\Throwable $t) {
					$this->plugin->getLogger()->warning('Unable to load dependency '.$dependency->getName().'.');
					$this->plugin->getLogger()->logException($t, $t->getTrace());
				}finally{
					$latch->countDown();
				}
			}));
		}

		try{
			$latch->await();
		}catch(\Exception $e) {
			// todo: thread interrupt impl
		}
	}

	private function loadDependency(Dependency $dependency) : void {
		if($this->loaded[$dependency->getName()] !== null) {
			return;
		}

		$file = $this->remapDependency($dependency, $this->downloadDependency($dependency));

		$this->loaded[$dependency->getName()] = $file;

		if($this->registry->shouldAutoload($dependency)) {
			$this->plugin->getClassPathAppender()->addClassToClasspath($file);
		}
	}

	private function downloadDependency(Dependency $dependency) : string {
		$file = $this->cacheDirectory.$dependency->getFileName(null);

		if(file_exists($file)) {
			return $file;
		}

		$lastError = null;
		foreach(DependencyRepository::getAll() as $repo) {
			try{
				$repo->download($dependency, $file);
				return $file;
			}catch(DependencyDownloadException $e) {
				$lastError = $e;
			}
		}

		throw $lastError;
	}

	private function remapDependency(Dependency $dependency, string $normalFile) {
		$rules = $dependency->getRelocations();
		$this->registry->applyRelocationSettings($dependency, $rules);

		if(empty($rules)) {
			return $normalFile;
		}

		$remappedFile = $this->cacheDirectory.$dependency->getFileName(DependencyRegistry::isGsonRelocated() ? 'remapped-legacy' : 'remapped');

		if(file_exists($remappedFile)) {
			return $remappedFile;
		}

		$this->getRelocationHandler()->remap($normalFile, $remappedFile, $rules);
		return $remappedFile;
	}

	private static function setupCacheDirectory(LuckPerms $plugin) : string {
		$cacheDirectory = $plugin->getDataFolder().'libs'.DIRECTORY_SEPARATOR;
		try{
			MoreFiles::createDirectoriesIfNotExists($cacheDirectory);
		}catch(\Exception $e) {
			throw new \RuntimeException('Unable to create libs directory', $e->getCode(), $e);
		}
		return $cacheDirectory;
	}
}