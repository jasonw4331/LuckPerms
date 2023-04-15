<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\config;

use jasonwynn10\LuckPerms\api\context\ContextSatisfyMode;
use jasonwynn10\LuckPerms\api\metastacking\DuplicateRemovalFunction;
use jasonwynn10\LuckPerms\api\metastacking\MetaStackDefinition;
use jasonwynn10\LuckPerms\api\model\data\TemporaryNodeMergeStrategy;
use jasonwynn10\LuckPerms\api\query\QueryOptions;
use jasonwynn10\LuckPerms\cacheddata\type\SimpleMetaValueSelector;
use jasonwynn10\LuckPerms\cacheddata\type\Strategy;
use jasonwynn10\LuckPerms\config\generic\adapter\ConfigurationAdapter;
use jasonwynn10\LuckPerms\config\generic\key\ConfigKeyFactory;
use jasonwynn10\LuckPerms\config\generic\key\SimpleConfigKey;
use jasonwynn10\LuckPerms\config\generic\KeyedConfiguration;
use jasonwynn10\LuckPerms\context\calculator\WorldNameRewriter;
use jasonwynn10\LuckPerms\graph\TraversalAlgorithm;
use jasonwynn10\LuckPerms\metastacking\SimpleMetaStackDefinition;
use jasonwynn10\LuckPerms\metastacking\StandardStackElements;
use jasonwynn10\LuckPerms\model\AllParentsByWeight;
use jasonwynn10\LuckPerms\model\ParentsByWeight;
use jasonwynn10\LuckPerms\model\Stored;
use jasonwynn10\LuckPerms\query\Flag;
use jasonwynn10\LuckPerms\query\QueryMode;
use jasonwynn10\LuckPerms\query\QueryOptionsBuilderImpl;
use jasonwynn10\LuckPerms\storage\implementation\split\SplitStorageType;
use jasonwynn10\LuckPerms\storage\misc\StorageCredentials;
use jasonwynn10\LuckPerms\storage\StorageType;
use pocketmine\utils\RegistryTrait;
use Ramsey\Collection\Map\TypedMap;
use Ramsey\Collection\Set;
use function array_filter;
use function array_keys;
use function array_map;
use function array_values;
use function count;
use function intval;
use function mb_strtolower;
use function preg_match;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static SimpleConfigKey ALLOW_INVALID_USERNAMES()
 * @method static SimpleConfigKey APPLYING_REGEX()
 * @method static SimpleConfigKey APPLYING_SHORTHAND()
 * @method static SimpleConfigKey APPLYING_WILDCARDS()
 * @method static SimpleConfigKey APPLY_ATTACHMENT_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_BUKKIT_ATTACHMENT_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_BUKKIT_CHILD_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_BUKKIT_DEFAULT_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_BUNGEE_CONFIG_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_CHILD_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_DEFAULT_NEGATIONS_BEFORE_WILDCARDS()
 * @method static SimpleConfigKey APPLY_DEFAULT_PERMISSIONS()
 * @method static SimpleConfigKey APPLY_SPONGE_DEFAULT_SUBJECTS()
 * @method static SimpleConfigKey AUTO_INSTALL_TRANSLATIONS()
 * @method static SimpleConfigKey AUTO_OP()
 * @method static SimpleConfigKey AUTO_PUSH_UPDATES()
 * @method static SimpleConfigKey BROADCAST_RECEIVED_LOG_ENTRIES()
 * @method static SimpleConfigKey BYTEBIN_URL()
 * @method static SimpleConfigKey CANCEL_FAILED_LOGINS()
 * @method static SimpleConfigKey COMMANDS_ALLOW_OP()
 * @method static SimpleConfigKey CONTEXT_SATISFY_MODE()
 * @method static SimpleConfigKey DATABASE_VALUES()
 * @method static SimpleConfigKey DEBUG_LOGINS()
 * @method static SimpleConfigKey DISABLED_CONTEXTS()
 * @method static SimpleConfigKey DISABLED_CONTEXT_CALCULATORS()
 * @method static SimpleConfigKey FABRIC_INTEGRATED_SERVER_OWNER_BYPASSS_CHECKS()
 * @method static SimpleConfigKey GLOBAL_QUERY_OPTIONS()
 * @method static SimpleConfigKey GROUP_NAME_REWRITES()
 * @method static SimpleConfigKey GROUP_WEIGHTS()
 * @method static SimpleConfigKey INHERITANCE_TRAVERSAL_ALGORITHM()
 * @method static SimpleConfigKey LOG_NOTIFY()
 * @method static SimpleConfigKey LOG_NOTIFY_FILTERED_DESCRIPTIONS()
 * @method static SimpleConfigKey MESSAGING_SERVICE()
 * @method static SimpleConfigKey META_VALUE_SELECTOR()
 * @method static SimpleConfigKey MONGODB_COLLECTION_PREFIX()
 * @method static SimpleConfigKey MONGODB_CONNECTION_URI()
 * @method static SimpleConfigKey OPS_ENABLED()
 * @method static SimpleConfigKey POST_TRAVERSAL_INHERITANCE_SORT()
 * @method static SimpleConfigKey PREFIX_FORMATTING_OPTIONS()
 * @method static SimpleConfigKey PREVENT_PRIMARY_GROUP_REMOVAL()
 * @method static SimpleConfigKey PRIMARY_GROUP_CALCULATION()
 * @method static SimpleConfigKey PRIMARY_GROUP_CALCULATION_METHOD()
 * @method static SimpleConfigKey PUSH_LOG_ENTRIES()
 * @method static SimpleConfigKey RABBITMQ_ADDRESS()
 * @method static SimpleConfigKey RABBITMQ_ENABLED()
 * @method static SimpleConfigKey RABBITMQ_PASSWORD()
 * @method static SimpleConfigKey RABBITMQ_USERNAME()
 * @method static SimpleConfigKey RABBITMQ_VIRTUAL_HOST()
 * @method static SimpleConfigKey REDIS_ADDRESS()
 * @method static SimpleConfigKey REDIS_ENABLED()
 * @method static SimpleConfigKey REDIS_PASSWORD()
 * @method static SimpleConfigKey REDIS_SSL()
 * @method static SimpleConfigKey REGISTER_COMMAND_LIST_DATA()
 * @method static SimpleConfigKey REQUIRE_SENDER_GROUP_MEMBERSHIP_TO_MODIFY()
 * @method static SimpleConfigKey RESOLVE_COMMAND_SELECTORS()
 * @method static SimpleConfigKey SERVER()
 * @method static SimpleConfigKey SKIP_BULKUPDATE_CONFIRMATION()
 * @method static SimpleConfigKey SPLIT_STORAGE()
 * @method static SimpleConfigKey SPLIT_STORAGE_OPTIONS()
 * @method static SimpleConfigKey SQL_TABLE_PREFIX()
 * @method static SimpleConfigKey STORAGE_METHOD()
 * @method static SimpleConfigKey SUFFIX_FORMATTING_OPTIONS()
 * @method static SimpleConfigKey SYNC_TIME()
 * @method static SimpleConfigKey TEMPORARY_ADD_BEHAVIOUR()
 * @method static SimpleConfigKey TREE_VIEWER_URL_PATTERN()
 * @method static SimpleConfigKey UPDATE_CLIENT_COMMAND_LIST()
 * @method static SimpleConfigKey USE_ARGUMENT_BASED_COMMAND_PERMISSIONS()
 * @method static SimpleConfigKey USE_SERVER_UUID_CACHE()
 * @method static SimpleConfigKey USE_VAULT_SERVER()
 * @method static SimpleConfigKey VAULT_GROUP_USE_DISPLAYNAMES()
 * @method static SimpleConfigKey VAULT_IGNORE_WORLD()
 * @method static SimpleConfigKey VAULT_INCLUDING_GLOBAL()
 * @method static SimpleConfigKey VAULT_NPC_GROUPS()
 * @method static SimpleConfigKey VAULT_NPC_OP_STATUS()
 * @method static SimpleConfigKey VAULT_SERVER()
 * @method static SimpleConfigKey VAULT_UNSAFE_LOOKUPS()
 * @method static SimpleConfigKey VERBOSE_VIEWER_URL_PATTERN()
 * @method static SimpleConfigKey WATCH_FILES()
 * @method static SimpleConfigKey WEB_EDITOR_URL_PATTERN()
 * @method static SimpleConfigKey WORLD_REWRITES()
 */
final class ConfigKeys{
	use RegistryTrait {
		_registryRegister as register;
	}

	/** @var SimpleConfigKey[] $KEYS */
	private static array $KEYS = [];

	private function __construct(){
		//NOOP
	}

	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var SimpleConfigKey[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		self::register("server", ConfigKeyFactory::lowercaseStringKey('server', 'global'));
		self::register("sync_time", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c){
			$val = $c->getInteger('sync-minutes', -1);
			if($val === -1){
				$val = $c->getInteger('data.sync-minutes', -1);
			}
			return $val;
		})));
		self::register("global_query_options", ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : QueryOptions{
			$flags = new Set(Flag::class, [Flag::RESOLVE_INHERITANCE()]);
			if($c->getBoolean('include-global', true)){
				$flags->add(Flag::INCLUDE_NODES_WITHOUT_SERVER_CONTEXT());
			}
			if($c->getBoolean('include-global-world', true)){
				$flags->add(Flag::INCLUDE_NODES_WITHOUT_WORLD_CONTEXT());
			}
			if($c->getBoolean('apply-global-groups', true)){
				$flags->add(Flag::APPLY_INHERITANCE_NODES_WITHOUT_SERVER_CONTEXT());
			}
			if($c->getBoolean('apply-global-world-groups', true)){
				$flags->add(Flag::APPLY_INHERITANCE_NODES_WITHOUT_WORLD_CONTEXT());
			}

			return (new QueryOptionsBuilderImpl(QueryMode::CONTEXTUAL()))->flags($flags)->build();
		}));
		self::register("context_satisfy_mode", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : ContextSatisfyMode => mb_strtolower($c->getString('context-satisfy-mode', 'at-least-one-value-per-key')) === 'all-values-per-key' ? ContextSatisfyMode::ALL_VALUES_PER_KEY() : ContextSatisfyMode::AT_LEAST_ONE_VALUE_PER_KEY()));
		self::register("disabled_contexts", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : Set{
			$contexts = $c->getStringList('disabled-contexts', []);
			$contexts = array_map('mb_strtolower', $contexts);
			return new Set("string", $contexts);
		})));
		self::register("use_server_uuid_cache", ConfigKeyFactory::booleanKey('use-server-uuid-cache', false));
		self::register("allow_invalid_usernames", ConfigKeyFactory::booleanKey('allow-invalid-usernames', false));
		self::register("skip_bulkupdate_confirmation", ConfigKeyFactory::booleanKey('skip-bulkupdate-confirmation', false));
		self::register("debug_logins", ConfigKeyFactory::booleanKey('debug-logins', false));
		self::register("cancel_failed_logins", ConfigKeyFactory::booleanKey('cancel-failed-logins', false));
		self::register("update_client_command_list", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('update-client-command-list', true)));
		self::register("register_command_list_data", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('register-command-list-data', true)));
		self::register("resolve_command_selectors", ConfigKeyFactory::booleanKey('resolve-command-selectors', false));
		self::register("temporary_add_behaviour", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : TemporaryNodeMergeStrategy => match (mb_strtolower($c->getString('temporary-add-behaviour', 'deny'))) {
			'accumulate' => TemporaryNodeMergeStrategy::ADD_NEW_DURATION_TO_EXISTING(),
			'replace' => TemporaryNodeMergeStrategy::REPLACE_EXISTING_IF_DURATION_LONGER(),
			default => TemporaryNodeMergeStrategy::NONE(),
		}));
		self::register("primary_group_calculation_method", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : string{
			$option = mb_strtolower($c->getString('primary-group-calculation', 'stored'));
			if($option !== 'stored' && $option !== 'parents-by-weight' && $option !== 'all-parents-by-weight')
				$option = 'stored';
			return $option;
		})));
		self::register("primary_group_calculation", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : callable => match (self::PRIMARY_GROUP_CALCULATION_METHOD()->get($c)) {
			default => [Stored::class, "__construct"],
			'parents-by-weight' => [ParentsByWeight::class, "__construct"],
			'all-parents-by-weight' => [AllParentsByWeight::class, "__construct"],
		})));
		self::register("prevent_primary_group_removal", ConfigKeyFactory::booleanKey('prevent-primary-group-removal', true));
		self::register("use_argument_based_command_permissions", ConfigKeyFactory::booleanKey('argument-based-command-permissions', false));
		self::register("require_sender_group_membership_to_modify", ConfigKeyFactory::booleanKey('require-sender-group-membership-to-modify', false));
		self::register("applying_wildcards", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-wildcards', false)));
		/*self::register("applying_wildcards_sponge", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : bool{
			$def = $c->getPlugin()->getBootstrap()->getType() === PlatformType::SPONGE();
			return $c->getBoolean('apply-sponge-implicit-wildcards', $def);
		})));*/
		self::register("apply_default_negations_before_wildcards", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-default-negated-permissions-before-wildcards', false)));
		self::register("applying_regex", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-regex', true)));
		self::register("applying_shorthand", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-shorthand', true)));
		self::register("apply_bukkit_child_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-bukkit-child-permissions', true)));
		self::register("apply_bukkit_default_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-bukkit-default-permissions', true)));
		self::register("apply_bukkit_attachment_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-bukkit-attachment-permissions', true)));
		self::register("apply_child_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-child-permissions', true)));
		self::register("apply_default_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-default-permissions', true)));
		self::register("apply_attachment_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-attachment-permissions', true)));
		self::register("apply_bungee_config_permissions", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-bungee-config-permissions', false)));
		self::register("apply_sponge_default_subjects", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('apply-sponge-default-subjects', true)));
		self::register("inheritance_traversal_algorithm", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : TraversalAlgorithm => match (mb_strtolower($c->getString('inheritance-traversal-algorithm', 'depth-first-pre-order'))) {
			'breadth-first' => TraversalAlgorithm::BREADTH_FIRST(),
			'depth-first-post-order' => TraversalAlgorithm::DEPTH_FIRST_POST_ORDER(),
			default => TraversalAlgorithm::DEPTH_FIRST_PRE_ORDER(),
		}));
		self::register("post_traversal_inheritance_sort", ConfigKeyFactory::booleanKey('post-traversal-inheritance-sort', false));
		self::register("meta_value_selector", ConfigKeyFactory::key(static function(ConfigurationAdapter $c){
			$defaultStrategy = Strategy::parse($c->getString('meta-value-selection-default', 'inheritance'));
			$strategies = $c->getStringMap('meta-value-selection', new TypedMap("string", Strategy::class, []));
			/** @var Strategy[] $strategies */
			$strategies = array_filter(array_map(static fn(string $value) => Strategy::parse($value) ?? null, array_values($strategies)), 'is_object');
			return new SimpleMetaValueSelector($strategies, $defaultStrategy);
		}));
		self::register("group_weights", ConfigKeyFactory::key(static function(ConfigurationAdapter $c){
			/** @var array<string, int> $weights */
			$weights = $c->getStringMap('group-weight', new TypedMap("string", "int", []));
			return array_map(static fn(string $key, int $value) => [mb_strtolower($key), intval($value)], array_keys($weights), array_values($weights));
		}));
		self::register("prefix_formatting_options", ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : MetaStackDefinition{
			$format = $c->getStringList('meta-formatting.prefix.format', []);
			if(count($format) < 1)
				$format[] = 'highest';
			$startSpacer = $c->getString('meta-formatting.prefix.start-spacer', '');
			$middleSpacer = $c->getString('meta-formatting.prefix.middle-spacer', ' ');
			$endSpacer = $c->getString('meta-formatting.prefix.end-spacer', '');
			$duplicateRemovalFunction = match (mb_strtolower($c->getString('meta-formatting.prefix.duplicates', ''))) {
				'first-only' => DuplicateRemovalFunction::FIRST_ONLY(),
				'last-only' => DuplicateRemovalFunction::LAST_ONLY(),
				default => DuplicateRemovalFunction::RETAIN_ALL(),
			};
			return new SimpleMetaStackDefinition(StandardStackElements::parseList($c->getPlugin(), $format), $duplicateRemovalFunction, $startSpacer, $middleSpacer, $endSpacer);
		}));
		self::register("suffix_formatting_options", ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : MetaStackDefinition{
			$format = $c->getStringList('meta-formatting.suffix.format', []);
			if(count($format) < 1)
				$format[] = 'highest';
			$startSpacer = $c->getString('meta-formatting.suffix.start-spacer', '');
			$middleSpacer = $c->getString('meta-formatting.suffix.middle-spacer', ' ');
			$endSpacer = $c->getString('meta-formatting.suffix.end-spacer', '');
			$duplicateRemovalFunction = match (mb_strtolower($c->getString('meta-formatting.suffix.duplicates', ''))) {
				'first-only' => DuplicateRemovalFunction::FIRST_ONLY(),
				'last-only' => DuplicateRemovalFunction::LAST_ONLY(),
				default => DuplicateRemovalFunction::RETAIN_ALL(),
			};
			return new SimpleMetaStackDefinition(StandardStackElements::parseList($c->getPlugin(), $format), $duplicateRemovalFunction, $startSpacer, $middleSpacer, $endSpacer);
		}));
		self::register("log_notify", ConfigKeyFactory::booleanKey('log-notify', true));
		self::register("log_notify_filtered_descriptions", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : array => array_filter($c->getStringList('log-notify-filtered-descriptions', []), static fn(string $value) : bool => preg_match($value, '') === false)));
		self::register("auto_install_translations", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('auto-install-translations', true)));
		self::register("auto_op", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('auto-op', false)));
		self::register("ops_enabled", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : bool => !self::AUTO_OP()->get($c) && $c->getBoolean('enable-ops', true))));
		self::register("commands_allow_op", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('commands-allow-op', true)));
		self::register("vault_unsafe_lookups", ConfigKeyFactory::booleanKey('vault-unsafe-lookups', false));
		self::register("vault_group_use_displaynames", ConfigKeyFactory::booleanKey('vault-group-use-displaynames', true));
		self::register("vault_npc_groups", ConfigKeyFactory::stringKey('vault-npc-group', 'default'));
		self::register("vault_npc_op_status", ConfigKeyFactory::booleanKey('vault-npc-op-status', false));
		self::register("use_vault_server", ConfigKeyFactory::booleanKey('use-vault-server', false));
		self::register("vault_server", ConfigKeyFactory::lowercaseStringKey('server', 'global'));
		self::register("vault_including_global", ConfigKeyFactory::booleanKey('vault-include-global', true));
		self::register("vault_ignore_world", ConfigKeyFactory::booleanKey('vault-ignore-world', false));
		self::register("fabric_integrated_server_owner_bypasss_checks", ConfigKeyFactory::booleanKey('integrated-server-owner-bypasses-checks', true));
		self::register("disabled_context_calculators", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : array => array_map(static fn(string $value) : string => mb_strtolower($value), $c->getStringList('disabled-context-calculators', []))));
		self::register("world_rewrites", ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : WorldNameRewriter => WorldNameRewriter::of(new TypedMap('string', 'string', array_map(static fn(string $key, string $value) => [mb_strtolower($key), mb_strtolower($value)], array_keys($array = $c->getStringMap('world-rewrite', new TypedMap("string", "string", []))), array_values($array))))));
		self::register("group_name_rewrites", ConfigKeyFactory::mapKey('group-name-rewrite'));
		self::register("database_values", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : StorageCredentials{
			$maxPoolSize = $c->getInteger("data.pool-settings.maximum-pool-size", $c->getInteger("data.pool-size", 10));
			$minIdle = $c->getInteger("data.pool-settings.minimum-idle", $maxPoolSize);
			$maxLifetime = $c->getInteger("data.pool-settings.maximum-lifetime", 1800000);
			$keepAliveTime = $c->getInteger("data.pool-settings.keepalive-time", 0);
			$connectionTimeout = $c->getInteger("data.pool-settings.connection-timeout", 5000);
			$props = $c->getStringMap("data.pool-settings.properties", new TypedMap("string", "string", []));

			return new StorageCredentials(
				$c->getString("data.address", null),
				$c->getString("data.database", null),
				$c->getString("data.username", null),
				$c->getString("data.password", null),
				$maxPoolSize, $minIdle, $maxLifetime, $keepAliveTime, $connectionTimeout, $props
			);
		})));
		self::register("sql_table_prefix", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : string => $c->getString('data.table-prefix', $c->getString('data.table_prefix', 'luckperms_')))));
		self::register("mongodb_collection_prefix", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : string => $c->getString('data.mongodb-collection-prefix', $c->getString('data.mongodb_collection_prefix', '')))));
		self::register("mongodb_connection_uri", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : string => $c->getString('data.mongodb-connection-uri', $c->getString('data.mongodb_connection_uri', '')))));
		self::register("storage_method", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static fn(ConfigurationAdapter $c) : StorageType => StorageType::parse($c->getString('storage-method', 'sqlite'), StorageType::SQLITE()))));
		self::register("watch_files", ConfigKeyFactory::booleanKey('watch-files', true));
		self::register("split_storage", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('split-storage.enabled', false)));
		self::register("split_storage_options", ConfigKeyFactory::notReloadable(ConfigKeyFactory::key(static function(ConfigurationAdapter $c) : TypedMap{
			$map = new TypedMap(SplitStorageType::class, StorageType::class, []);
			$map->put(SplitStorageType::USER()->name(), StorageType::parse($c->getString('split-storage.user', 'sqlite'), StorageType::SQLITE()));
			$map->put(SplitStorageType::GROUP()->name(), StorageType::parse($c->getString('split-storage.group', 'sqlite'), StorageType::SQLITE()));
			$map->put(SplitStorageType::TRACK()->name(), StorageType::parse($c->getString('split-storage.track', 'sqlite'), StorageType::SQLITE()));
			$map->put(SplitStorageType::UUID()->name(), StorageType::parse($c->getString('split-storage.uuid', 'sqlite'), StorageType::SQLITE()));
			$map->put(SplitStorageType::LOG()->name(), StorageType::parse($c->getString('split-storage.log', 'sqlite'), StorageType::SQLITE()));
			return clone $map;
		})));
		self::register("messaging_service", ConfigKeyFactory::notReloadable(ConfigKeyFactory::lowercaseStringKey('messaging-service', 'auto')));
		self::register("auto_push_updates", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('auto-push-updates', true)));
		self::register("push_log_entries", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('push-log-entries', true)));
		self::register("broadcast_received_log_entries", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('broadcast-received-log-entries', false)));
		self::register("redis_enabled", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('redis.enabled', false)));
		self::register("redis_address", ConfigKeyFactory::notReloadable(ConfigKeyFactory::stringKey('redis.address', null)));
		self::register("redis_password", ConfigKeyFactory::notReloadable(ConfigKeyFactory::stringKey('redis.password', '')));
		self::register("redis_ssl", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('redis.ssl', false)));
		self::register("rabbitmq_enabled", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('rabbitmq.enabled', false)));
		self::register("rabbitmq_address", ConfigKeyFactory::notReloadable(ConfigKeyFactory::booleanKey('rabbitmq.address', false)));
		self::register("rabbitmq_virtual_host", ConfigKeyFactory::notReloadable(ConfigKeyFactory::stringKey('rabbitmq.vhost', '/')));
		self::register("rabbitmq_username", ConfigKeyFactory::notReloadable(ConfigKeyFactory::stringKey('rabbitmq.username', 'guest')));
		self::register("rabbitmq_password", ConfigKeyFactory::notReloadable(ConfigKeyFactory::stringKey('rabbitmq.password', 'guest')));
		self::register("bytebin_url", ConfigKeyFactory::stringKey('bytebin-url', 'https://bytebin.lucko.me/'));
		self::register("web_editor_url_pattern", ConfigKeyFactory::stringKey('web-editor-url', 'https://luckperms.net/editor/'));
		self::register("verbose_viewer_url_pattern", ConfigKeyFactory::stringKey('verbose-viewer-url', 'https://luckperms.net/verbose/'));
		self::register("tree_viewer_url_pattern", ConfigKeyFactory::stringKey('tree-viewer-url', 'https://luckperms.net/treeview/'));

		self::$KEYS = KeyedConfiguration::initialise(self::class);
	}

	/**
	 * @return SimpleConfigKey[]
	 */
	public static function getKeys() : array{
		return self::$KEYS;
	}
}
