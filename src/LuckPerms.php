<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms;

use jasonwynn10\LuckPerms\actionlog\LogDispatcher;
use jasonwynn10\LuckPerms\api\ApiRegistrationUtil;
use jasonwynn10\LuckPerms\api\LuckPermsApiProvider;
use jasonwynn10\LuckPerms\calculator\CalculatorFactory;
use jasonwynn10\LuckPerms\commands\generic\permission\CommandPermission;
use jasonwynn10\LuckPerms\config\ConfigKeys;
use jasonwynn10\LuckPerms\config\LuckPermsConfiguration;
use jasonwynn10\LuckPerms\context\ConfigurationContextCalculator;
use jasonwynn10\LuckPerms\context\ContextManager;
use jasonwynn10\LuckPerms\context\PlayerCalculator;
use jasonwynn10\LuckPerms\event\EventDispatcher;
use jasonwynn10\LuckPerms\event\gen\GeneratedEventClass;
use jasonwynn10\LuckPerms\extension\SimpleExtensionManager;
use jasonwynn10\LuckPerms\inheritance\InheritanceGraphFactory;
use jasonwynn10\LuckPerms\inject\permissible\LuckPermsPermissible;
use jasonwynn10\LuckPerms\inject\permissible\Mode;
use jasonwynn10\LuckPerms\inject\permissible\PermissibleInjector;
use jasonwynn10\LuckPerms\inject\permissible\PermissibleMonitoringInjector;
use jasonwynn10\LuckPerms\inject\server\InjectorDefaultsMap;
use jasonwynn10\LuckPerms\inject\server\InjectorPermissionMap;
use jasonwynn10\LuckPerms\inject\server\InjectorSubscriptionMap;
use jasonwynn10\LuckPerms\inject\server\LuckPermsDefaultsMap;
use jasonwynn10\LuckPerms\inject\server\LuckPermsPermissionMap;
use jasonwynn10\LuckPerms\inject\server\LuckPermsSubscriptionMap;
use jasonwynn10\LuckPerms\listeners\AutoOpListener;
use jasonwynn10\LuckPerms\listeners\ConnectionListener;
use jasonwynn10\LuckPerms\listeners\PlatformListener;
use jasonwynn10\LuckPerms\locale\TranslationManager;
use jasonwynn10\LuckPerms\locale\TranslationRepository;
use jasonwynn10\LuckPerms\messaging\InternalMessagingService;
use jasonwynn10\LuckPerms\messaging\MessagingFactory;
use jasonwynn10\LuckPerms\model\manager\group\StandardGroupManager;
use jasonwynn10\LuckPerms\model\manager\track\StandardTrackManager;
use jasonwynn10\LuckPerms\model\manager\user\StandardUserManager;
use jasonwynn10\LuckPerms\model\User;
use jasonwynn10\LuckPerms\node\types\Permission;
use jasonwynn10\LuckPerms\storage\implementation\file\watcher\FileWatcher;
use jasonwynn10\LuckPerms\storage\misc\DataConstraints;
use jasonwynn10\LuckPerms\storage\Storage;
use jasonwynn10\LuckPerms\storage\StorageFactory;
use jasonwynn10\LuckPerms\tasks\Buffer;
use jasonwynn10\LuckPerms\tasks\CacheHousekeepingTask;
use jasonwynn10\LuckPerms\tasks\ExpireTemporaryTask;
use jasonwynn10\LuckPerms\tasks\SyncTask;
use jasonwynn10\LuckPerms\treeview\PermissionRegistry;
use jasonwynn10\LuckPerms\util\AbstractConnectionListener;
use jasonwynn10\LuckPerms\verbose\VerboseHandler;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use Ramsey\Uuid\Uuid;

class LuckPerms extends PluginBase{
	private static LuckPerms $instance;
	private static ?ConsoleCommandSender $consoleCommandSender = null;

    private TranslationManager $translationManager;

	private VerboseHandler $verboseHandler;
    private PermissionRegistry $permissionRegistry;
    private LogDispatcher $logDispatcher;
    private LuckPermsConfiguration $configuration;
    private BytebinClient $bytebin;
    private TranslationRepository $translationRepository;
    private ?FileWatcher $fileWatcher = null;
    private Storage $storage;
    private ?InternalMessagingService $messagingService = null;
    private Buffer $syncTaskBuffer;
    private InheritanceGraphFactory $inheritanceGraphFactory;
    private CalculatorFactory $calculatorFactory;
    private LuckPermsApiProvider $apiProvider;
    private EventDispatcher $eventDispatcher;
    private SimpleExtensionManager $extensionManager;

	private SenderFactory $senderFactory;
	private AbstractConnectionListener $connectionListener;
	private LuckPermsCommandExecutor $commandManager;
	private StandardUserManager $userManager;
    private StandardGroupManager $groupManager;
    private StandardTrackManager $trackManager;
    private ContextManager $contextManager;
    private LuckPermsSubscriptionMap $subscriptionMap;
    private LuckPermsPermissionMap $permissionMap;
    private LuckPermsDefaultsMap $defaultPermissionMap;

	public static function getInstance() : LuckPerms{
		return self::$instance;
	}

	public function onLoad() : void {
		self::$instance = $this;

		foreach($this->getServer()->getBroadcastChannelSubscribers(Server::BROADCAST_CHANNEL_ADMINISTRATIVE) as $sender) {
			/** @noinspection PhpFieldAssignmentTypeMismatchInspection */
			self::$consoleCommandSender = $sender; // we know there is only 1 broadcast subscriber at this point: The console.
		}

		$this->translationManager = new TranslationManager($this);
		$this->translationManager->reload();
	}

	public function onEnable() : void {
		$this->senderFactory = new SenderFactory($this);

		$this->verboseHandler = new VerboseHandler($this->getScheduler());
		$this->permissionRegistry = new PermissionRegistry($this->getScheduler());
		$this->logDispatcher = new LogDispatcher($this);

		$this->getLogger()->debug("Loading configuration...");
		$this->configuration = new LuckPermsConfiguration($this, $this->getConfig());

		$httpClient = OkHttpClient::builder()->callTimeout(15)->build();

		$this->bytebin = new BytebinClient($httpClient, $this->getConfiguration()->get(ConfigKeys::BYTEBIN_URL()), 'luckperms');

		$this->translationRepository = new TranslationRepository($this);
		$this->translationRepository->scheduleRefresh();

		$storageFactory = new StorageFactory($this);
		$storageTypes = $storageFactory->getRequiredTypes();
		$this->dependencyManager->loadStorageDependencies($storageTypes);

		$this->connectionListener = new ConnectionListener($this);
		$this->getServer()->getPluginManager()->registerEvents($this->connectionListener, $this);
		$this->getServer()->getPluginManager()->registerEvents(new PlatformListener($this), $this);

		if($this->getConfiguration()->get(ConfigKeys::WATCH_FILES())) {
			try {
				$this->fileWatcher = new FileWatcher($this, $this->getDataFolder());
			}catch(\Throwable $e) {
				$this->getLogger()->warning("Error occurred whilst trying to create a file watcher: ".$e->getMessage());
			}
		}

		$this->storage = $storageFactory->getInstance();
		$this->messagingService = (new MessagingFactory($this))->getInstance();

		$this->syncTaskBuffer = new Buffer($this);

		$command = $this->getCommand('luckperms');
		$this->commandManager = new LuckPermsCommandExecutor($this, $command);
		$this->commandManager->register();

		$this->getLogger()->debug("Loading internal permission managers...");
		$this->inheritanceGraphFactory = new InheritanceGraphFactory($this);

		$this->userManager = new StandardUserManager($this);
		$this->groupManager = new StandardGroupManager($this);
		$this->trackManager = new StandardTrackManager($this);

		$this->calculatorFactory = new CalculatorFactory($this);

		$this->contextManager = new ContextManager($this);

		$playerCalculator = new PlayerCalculator($this, $this->getConfiguration()->get(ConfigKeys::DISABLED_CONTEXTS()));
		$this->getServer()->getPluginManager()->registerEvents($playerCalculator, $this);
		$this->contextManager->registerCalculator($playerCalculator);

		$this->getContextManager()->registerCalculator(new ConfigurationContextCalculator($this->getConfiguration()));

		$injectors = [
			new InjectorSubscriptionMap($this),
			new InjectorPermissionMap($this),
			new InjectorDefaultsMap($this),
			new PermissibleMonitoringInjector($this, Mode::INJECT()),
		];
		foreach($injectors as $injector) {
			$injector->run();

			$this->getScheduler()->scheduleDelayedTask($injector, 1);
		}

		$this->apiProvider = new LuckPermsApiProvider($this);
		$this->eventDispatcher = new EventDispatcher(new EventBus($this, $this->apiProvider));
		$this->getScheduler()->scheduleTask(new ClosureTask(function() {
			GeneratedEventClass::preGenerate();
		}));
		ApiRegistrationUtil::registerProvider($this->apiProvider);
		$this->registerApiOnPlatform($this->apiProvider); //TODO: nukkitx and bukkit have this feature, not pocketmine

		$this->extensionManager = new SimpleExtensionManager($this);
		$this->extensionManager->loadExtensions($this->getDataFolder().'extensions'.DIRECTORY_SEPARATOR);

		$syncMins = $this->getConfiguration()->get(ConfigKeys::SYNC_TIME());
		if($syncMins > 0) {
			$this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() {
				$this->syncTaskBuffer->request();
			}), 20 * 60 * $syncMins);
		}

		$this->getLogger()->debug("Performing initial data load...");
		try {
			(new SyncTask($this))->run();
		}catch(\Exception $e) {
			$this->getLogger()->logException($e);
		}

		$this->getScheduler()->scheduleRepeatingTask(new ExpireTemporaryTask($this), 3 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new CacheHousekeepingTask($this), 2 * 20);

		$pluginManager = $this->getServer()->getPluginManager();
		$permDefault = $this->getConfiguration()->get(ConfigKeys::COMMANDS_ALLOW_OP()) ? DefaultPermissions::ROOT_OPERATOR : null;
		foreach(CommandPermission::getAll() as $permission) {
			$bukkitPermission = new Permission($permission->getPermission(), null, $permDefault);
			$pluginManager->removePermission($bukkitPermission);
			$pluginManager->addPermission($bukkitPermission);
		}

		if(!$this->getConfiguration()->get(ConfigKeys::OPS_ENABLED())) {
			$ops = $this->getServer()->getOps();
			$ops->setAll([]);
		}

		if($this->getConfiguration()->get(ConfigKeys::AUTO_OP())) {
			$this->getApiProvider()->getEventBus()->subscribe(new AutoOpListener($this));
		}

		foreach($this->getServer()->getOnlinePlayers() as $player) {
			$this->getScheduler()->scheduleTask(new ClosureTask(function() use ($player) {
				try{
					$user = $this->connectionListener->loadUser($player->getUniqueId(), $player->getName());
					if($user !== null) {
						$this->getScheduler()->scheduleTask(new ClosureTask(function() use ($player, $user) {
							try{
								$lpPermissible = new LuckPermsPermissible($player, $user, $this);
								PermissibleInjector::inject($player, $lpPermissible);
							}catch(\Throwable $t) {
								$this->getLogger()->error('Exception thrown when setting up permissions for '.$player->getUniqueId().' - '.$player->getName());
								$this->getLogger()->logException($t);
							}
						}));
					}
				}catch(\Exception $e) {
					$this->getLogger()->error('Exception occurred whilst loading data for '.$player->getUniqueId().' - '.$player->getName());
					$this->getLogger()->logException($e);
				}
			}));
		}

		$timeTaken = microtime(true) - $this->getServer()->getStartTime();
		$this->getLogger()->debug("Sucessfully enabled. (took {$timeTaken}ms)");
	}

	public function onDisable() : void {
		$this->getLogger()->debug('Starting shutdown process...');

		$this->getScheduler()->cancelAllTasks();

		$this->permissionRegistry->close();
		$this->verboseHandler->close();

		$this->extensionManager->close();

		foreach($this->getServer()->getOnlinePlayers() as $player) {
			try{
				PermissibleInjector::uninject($player, false);
			}catch(\Exception $e) {
				$this->getLogger()->error('Exception thrown when unloading permissions from '.$player->getUniqueId().' - '.$player->getName());
				$this->getLogger()->logException($e);
			}

			if($this->getConfiguration()->get(ConfigKeys::AUTO_OP())) {
				// TODO: deop players
				$player->unsetBasePermission(DefaultPermissions::ROOT_OPERATOR);
				$player->setBasePermission(DefaultPermissions::ROOT_USER, true);
			}

			$user = $this->getUserManager()->getIfLoaded($player->getUniqueId());
			if($user !== null) {
				$user->getCachedData()->invalidate();
				$this->getUserManager()->unload($user->getUniqueId());
			}
		}

		InjectorSubscriptionMap::uninject();
		InjectorPermissionMap::uninject();
		InjectorDefaultsMap::uninject();
		(new PermissibleMonitoringInjector($this, Mode::UNINJECT()))->run();

		if($this->messagingService !== null) {
			$this->getLogger()->debug('Closing messaging service...');
			$this->messagingService->close();
		}

		$this->getLogger()->debug('Closing storage...');
		$this->storage->shutdown();

		if($this->fileWatcher !== null) {
			$this->fileWatcher->close();
		}

		ApiRegistrationUtil::unregisterProvider();

		//TODO: shutdown async scheduler executor, pocketmine handles this for us

		$this->getClassPathAppender()->close();

		$this->getLogger()->debug('Goodbye!');
	}

	public function setMessagingService(InternalMessagingService $messagingService) {
		if($this->messagingService === null)
			$this->messagingService = $messagingService;
	}

	public function getPlayer(Uuid $uniqueId) : ?Player {
		foreach($this->getServer()->getOnlinePlayers() as $player)
			if($player->getUniqueId()->equals($uniqueId))
				return $player;
		return null;
	}

	public function lookupUniqueId(string $username) : ?Uuid {
		$uniqueId = $this->getStorage()->getPlayerUniqueId(strtolower($username));

		$this->getEventDispatcher()->dispatchUniqueIdLookup($username, $uniqueId);

		if($uniqueId == null and $this->getConfiguration()->get(ConfigKeys::USE_SERVER_UUID_CACHE())) {
			$uniqueId = null; // PocketMine has no UUID cache yet
		}

		return $uniqueId;
	}

	public function lookupUsername(Uuid|string $uniqueId) : ?string {
		$username = $this->getStorage()->getPlayerName($uniqueId);

		$username = $this->getEventDispatcher()->dispatchUsernameLookup($uniqueId, $username);

		if($username === null and $this->getConfiguration()->get(ConfigKeys::USE_SERVER_UUID_CACHE())) {
			$username = null;
		}

		return $username;
	}

	public function testUsernameValidity(string $username) : bool {
		if(DataConstraints::PLAYER_USERNAME_TEST_LENIENT()->test($username)) {
			return false;
		}

		$valid = $this->getConfiguration()->get(ConfigKeys::ALLOW_INVALID_USERNAMES()) or DataConstraints::PLAYER_USERNAME_TEST()->test($username);

		return $this->getEventDispatcher()->dispatchUsernameValidityCheck($username, $valid);
	}

    public function getTranslationManager() : TranslationManager {
        return $this->translationManager;
    }

    public function getVerboseHandler() : VerboseHandler {
        return $this->verboseHandler;
    }

    public function getPermissionRegistry() : PermissionRegistry {
        return $this->permissionRegistry;
    }

    public function getLogDispatcher() : LogDispatcher {
        return $this->logDispatcher;
    }

	public function getConfiguration() : LuckPermsConfiguration {
		return $this->configuration;
	}

	public function getBytebin() : BytebinClient {
        return $this->bytebin;
    }

	public function getQueryOptionsForUser(User $user) {
		return $this->contextManager->getQueryOptions($this->getPlayer($user->getUniqueId()));
	}

	public function getOnlineSenders() : array {
		return array_merge([$this->getConsoleSender()], array_map(function($player) {
			return $this->getSenderFactory()->wrap($player);
		}, $this->getServer()->getOnlinePlayers()));
	}

	public function getConsoleSender() {
		return $this->getSenderFactory()->wrap(self::$consoleCommandSender);
	}

	public function getSenderFactory() : SenderFactory {
		return $this->senderFactory;
	}

	public function getConnectionListener() : AbstractConnectionListener {
        return $this->connectionListener;
    }

    public function getCommandManager() : LuckPermsCommandExecutor {
        return $this->commandManager;
    }

    public function getUserManager() : StandardUserManager {
        return $this->userManager;
    }

    public function getGroupManager() : StandardGroupManager {
        return $this->groupManager;
    }

    public function getTrackManager() : StandardTrackManager {
        return $this->trackManager;
    }

    public function getContextManager() : ContextManager {
        return $this->contextManager;
    }

    public function getSubscriptionMap() : LuckPermsSubscriptionMap {
        return $this->subscriptionMap;
    }

	public function setSubscriptionMap(LuckPermsSubscriptionMap $subscriptionMap) : void {
        $this->subscriptionMap = $subscriptionMap;
    }

	public function getPermissionMap() : LuckPermsPermissionMap {
        return $this->permissionMap;
    }

    public function setPermissionMap(LuckPermsPermissionMap $permissionMap) : void {
		$this->permissionMap = $permissionMap;
	}

    public function getDefaultPermissionMap() : LuckPermsDefaultsMap {
        return $this->defaultPermissionMap;
    }

    public function setDefaultPermissionMap(LuckPermsDefaultsMap $defaultPermissionMap) : void {
		$this->defaultPermissionMap = $defaultPermissionMap;
	}

    public function getTranslationRepository() : TranslationRepository {
        return $this->translationRepository;
    }

    public function getFileWatcher() : ?FileWatcher {
        return $this->fileWatcher;
    }

    public function getStorage() : Storage {
        return $this->storage;
    }

    public function getMessagingService() : ?InternalMessagingService {
        return $this->messagingService;
    }

    public function getSyncTaskBuffer() : Buffer {
        return $this->syncTaskBuffer;
    }

    public function getInheritanceGraphFactory() : InheritanceGraphFactory {
        return $this->inheritanceGraphFactory;
    }

    public function getCalculatorFactory() : CalculatorFactory {
        return $this->calculatorFactory;
    }

    public function getApiProvider() : LuckPermsApiProvider {
        return $this->apiProvider;
    }

    public function getExtensionManager() : SimpleExtensionManager {
        return $this->extensionManager;
    }

    public function getEventDispatcher() : EventDispatcher {
        return $this->eventDispatcher;
    }
}