<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\config;

use jasonwynn10\LuckPerms\api\context\ContextSatisfyMode;
use jasonwynn10\LuckPerms\api\model\data\TemporaryNodeMergeStrategy;
use jasonwynn10\LuckPerms\cacheddata\type\SimpleMetaValueSelector;
use jasonwynn10\LuckPerms\cacheddata\type\Strategy;
use jasonwynn10\LuckPerms\graph\TraversalAlgorithm;
use jasonwynn10\LuckPerms\model\AllParentsByWeight;
use jasonwynn10\LuckPerms\model\ParentsByWeight;
use jasonwynn10\LuckPerms\model\Stored;
use jasonwynn10\LuckPerms\storage\misc\StorageCredentials;
use jasonwynn10\LuckPerms\storage\StorageType;
use jasonwynn10\LuckPerms\util\traits\ExtraRegistryTrait;
use pocketmine\utils\Config;
use Webmozart\PathUtil\Path;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static bool ALLOW_INVALID_USERNAMES()
 * @method static bool APPLYING_REGEX()
 * @method static bool APPLYING_SHORTHAND()
 * @method static bool APPLYING_WILDCARDS()
 * @method static bool APPLYING_WILDCARDS_SPONGE()
 * @method static bool APPLY_ATTACHMENT_PERMISSIONS()
 * @method static bool APPLY_BUKKIT_ATTACHMENT_PERMISSIONS()
 * @method static bool APPLY_BUKKIT_CHILD_PERMISSIONS()
 * @method static bool APPLY_BUKKIT_DEFAULT_PERMISSIONS()
 * @method static bool APPLY_BUNGEE_CONFIG_PERMISSIONS()
 * @method static bool APPLY_CHILD_PERMISSIONS()
 * @method static bool APPLY_DEFAULT_NEGATIONS_BEFORE_WILDCARDS()
 * @method static bool APPLY_DEFAULT_PERMISSIONS()
 * @method static bool APPLY_SPONGE_DEFAULT_SUBJECTS()
 * @method static bool AUTO_INSTALL_TRANSLATIONS()
 * @method static bool AUTO_OP()
 * @method static bool AUTO_PUSH_UPDATES()
 * @method static bool BROADCAST_RECEIVED_LOG_ENTRIES()
 * @method static string BYTEBIN_URL()
 * @method static bool CANCEL_FAILED_LOGINS()
 * @method static bool COMMANDS_ALLOW_OP()
 * @method static \jasonwynn10\LuckPerms\api\context\ContextSatisfyMode CONTEXT_SATISFY_MODE()
 * @method static \jasonwynn10\LuckPerms\storage\misc\StorageCredentials DATABASE_VALUES()
 * @method static bool DEBUG_LOGINS()
 * @method static bool FABRIC_INTEGRATED_SERVER_OWNER_BYPASSS_CHECKS()
 * @method static array GROUP_NAME_REWRITES()
 * @method static array GROUP_WEIGHTS()
 * @method static \jasonwynn10\LuckPerms\graph\TraversalAlgorithm INHERITANCE_TRAVERSAL_ALGORITHM()
 * @method static bool LOG_NOTIFY()
 * @method static array LOG_NOTIFY_FILTERED_DESCRIPTIONS()
 * @method static string MESSAGING_SERVICE()
 * @method static \jasonwynn10\LuckPerms\cacheddata\type\SimpleMetaValueSelector META_VALUE_SELECTOR()
 * @method static string MONGODB_COLLECTION_PREFIX()
 * @method static string MONGODB_CONNECTION_URI()
 * @method static bool OPS_ENABLED()
 * @method static bool POST_TRAVERSAL_INHERITANCE_SORT()
 * @method static bool PREVENT_PRIMARY_GROUP_REMOVAL()
 * @method static \jasonwynn10\LuckPerms\model\ParentsByWeight PRIMARY_GROUP_CALCULATION()
 * @method static string PRIMARY_GROUP_CALCULATION_METHOD()
 * @method static bool PUSH_LOG_ENTRIES()
 * @method static string RABBITMQ_ADDRESS()
 * @method static bool RABBITMQ_ENABLED()
 * @method static string RABBITMQ_PASSWORD()
 * @method static string RABBITMQ_USERNAME()
 * @method static string RABBITMQ_VIRTUAL_HOST()
 * @method static null REDIS_ADDRESS()
 * @method static bool REDIS_ENABLED()
 * @method static string REDIS_PASSWORD()
 * @method static bool REDIS_SSL()
 * @method static bool REGISTER_COMMAND_LIST_DATA()
 * @method static bool REQUIRE_SENDER_GROUP_MEMBERSHIP_TO_MODIFY()
 * @method static bool RESOLVE_COMMAND_SELECTORS()
 * @method static string SERVER()
 * @method static bool SKIP_BULKUPDATE_CONFIRMATION()
 * @method static bool SPLIT_STORAGE()
 * @method static array SPLIT_STORAGE_OPTIONS()
 * @method static string SQL_TABLE_PREFIX()
 * @method static \jasonwynn10\LuckPerms\storage\StorageType STORAGE_METHOD()
 * @method static int SYNC_TIME()
 * @method static \jasonwynn10\LuckPerms\api\model\data\TemporaryNodeMergeStrategy TEMPORARY_ADD_BEHAVIOUR()
 * @method static string TREE_VIEWER_URL_PATTERN()
 * @method static bool UPDATE_CLIENT_COMMAND_LIST()
 * @method static bool USE_ARGUMENT_BASED_COMMAND_PERMISSIONS()
 * @method static bool USE_SERVER_UUID_CACHE()
 * @method static bool USE_VAULT_SERVER()
 * @method static bool VAULT_GROUP_USE_DISPLAYNAMES()
 * @method static bool VAULT_IGNORE_WORLD()
 * @method static bool VAULT_INCLUDING_GLOBAL()
 * @method static string VAULT_NPC_GROUPS()
 * @method static bool VAULT_NPC_OP_STATUS()
 * @method static string VAULT_SERVER()
 * @method static bool VAULT_UNSAFE_LOOKUPS()
 * @method static string VERBOSE_VIEWER_URL_PATTERN()
 * @method static bool WATCH_FILES()
 * @method static string WEB_EDITOR_URL_PATTERN()
 * @method static array WORLD_REWRITES()
 */
final class ConfigKeys{
	use ExtraRegistryTrait;

	private function __construct(){
		//NOOP
	}

	protected static function register(string $name, mixed $member) : void{
		self::_registryRegister($name, $member);
	}

	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var mixed[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		$config = new Config(Path::join(\pocketmine\PATH, 'plugin_data', 'LuckPerms', 'config.yml'));
		self::register("server", $config->get('server', 'global'));
		self::register("sync_time", $config->get('sync-minutes', -1));
		self::register("global_query_options", (function() use($config) : QueryOptions {
				$flags = [];
				if($config->get('include-global', true)) {
					$flags[] = Flag::INCLUDE_NODES_WITHOUT_SERVER_CONTEXT();
				}
				if($config->get('include-global-world', true)) {
					$flags[] = Flag::INCLUDE_NODES_WITHOUT_WORLD_CONTEXT();
				}
				if($config->get('apply-global-groups', true)) {
					$flags[] = Flag::APPLY_INHERITANCE_NODES_WITHOUT_SERVER_CONTEXT();
				}
				if($config->get('apply-global-world-groups', true)) {
					$flags[] = Flag::APPLY_INHERITANCE_NODES_WITHOUT_WORLD_CONTEXT();
				}

				return (new QueryOptionsBuilderImpl(QueryMode::CONTEXTUAL()))->flags($flags)->build();
			})());
		self::register("context_satisfy_mode", $config->get('context-satisfy-mode', 'at-least-one-value-per-key') === 'all-values-per-key' ? ContextSatisfyMode::ALL_VALUES_PER_KEY() : ContextSatisfyMode::AT_LEAST_ONE_VALUE_PER_KEY());
		self::register("use_server_uuid_cache", $config->get('use-server-uuid-cache', false));
		self::register("allow_invalid_usernames", $config->get('allow-invalid-usernames', false));
		self::register("skip_bulkupdate_confirmation", $config->get('skip-bulkupdate-confirmation', false));
		self::register("debug_logins", $config->get('debug-logins', false));
		self::register("cancel_failed_logins", $config->get('cancel-failed-logins', false));
		self::register("update_client_command_list", $config->get('update-client-command-list', true));
		self::register("register_command_list_data", $config->get('register-command-list-data', true));
		self::register("resolve_command_selectors", $config->get('resolve-command-selectors', false));
		self::register("temporary_add_behaviour", match ($config->get('temporary-add-behaviour', 'deny')) {
				'accumulate' => TemporaryNodeMergeStrategy::ADD_NEW_DURATION_TO_EXISTING(),
				'replace' => TemporaryNodeMergeStrategy::REPLACE_EXISTING_IF_DURATION_LONGER(),
				default => TemporaryNodeMergeStrategy::NONE(),
			});
		self::register("primary_group_calculation_method", (function() use($config) : string {
				$option = $config->get('primary-group-calculation', 'stored');
				if($option !== 'stored' and $option !== 'parents-by-weight' and $option !== 'all-parents-by-weight')
					$option = 'stored';
				return $option;
			})());
		self::register("primary_group_calculation", match($config->get('primary-group-calculation', 'stored')) {
				default => new Stored(),
				'parents-by-weight' => new ParentsByWeight(),
				'all-parents-by-weight' => new AllParentsByWeight(),
			});
		self::register("prevent_primary_group_removal", $config->get('prevent-primary-group-removal', true));
		self::register("use_argument_based_command_permissions", $config->get('argument-based-command-permissions', false));
		self::register("require_sender_group_membership_to_modify", $config->get('require-sender-group-membership-to-modify', false));
		self::register("applying_wildcards", $config->get('apply-wildcards', false));
		self::register("applying_wildcards_sponge", false);
		self::register("apply_default_negations_before_wildcards", $config->get('apply-default-negated-permissions-before-wildcards', false));
		self::register("applying_regex", $config->get('apply-regex', true));
		self::register("applying_shorthand", $config->get('apply-shorthand', true));
		self::register("apply_bukkit_child_permissions", $config->get('apply-bukkit-child-permissions', true));
		self::register("apply_bukkit_default_permissions", $config->get('apply-bukkit-default-permissions', true));
		self::register("apply_bukkit_attachment_permissions", $config->get('apply-bukkit-attachment-permissions', true));
		self::register("apply_child_permissions", $config->get('apply-child-permissions', true));
		self::register("apply_default_permissions", $config->get('apply-default-permissions', true));
		self::register("apply_attachment_permissions", $config->get('apply-attachment-permissions', true));
		self::register("apply_bungee_config_permissions", $config->get('apply-bungee-config-permissions', false));
		self::register("apply_sponge_default_subjects", $config->get('apply-sponge-default-subjects', true));
		self::register("inheritance_traversal_algorithm", match($config->get('inheritance-traversal-algorithm', 'depth-first-pre-order')) {
				'breadth-first' => TraversalAlgorithm::BREADTH_FIRST(),
				'depth-first-post-order' => TraversalAlgorithm::DEPTH_FIRST_POST_ORDER(),
				default => TraversalAlgorithm::DEPTH_FIRST_PRE_ORDER(),
			});
		self::register("post_traversal_inheritance_sort", $config->get('post-traversal-inheritance-sort', false));
		self::register("meta_value_selector", (function() use($config) {
				$defaultStrategy = Strategy::parse($config->get('meta-value-selection-default', 'inheritance'));
				$strategies = $config->get('meta-value-selection', []);
				/** @var Strategy[] $strategies */
				$strategies = array_filter(array_map(function($value) {
					return Strategy::parse($value) ?? null;
				}, array_values($strategies)));
				return new SimpleMetaValueSelector($strategies, $defaultStrategy);
			})());
		self::register("group_weights", (function() use($config) : array {
				$weights = $config->get('group-weight', []);
				return array_map(function($key, $value) {
					return [strtolower($key), intval($value)];
				}, array_keys($weights), array_values($weights));
			})());
		self::register("prefix_formatting_options", (function() use($config) : MetaStackDefinition {
				$format = $config->getNested('meta-formatting.prefix.format', []);
				if(count($format) < 1)
					$format[] = 'highest';
				$startSpacer = $config->getNested('meta-formatting.prefix.start-spacer', '');
				$middleSpacer = $config->getNested('meta-formatting.prefix.middle-spacer', ' ');
				$endSpacer = $config->getNested('meta-formatting.prefix.end-spacer', '');
				$duplicateRemovalFunction = match($config->getNested('meta-formatting.prefix.duplicates', '')) {
					'first-only' => DuplicateRemovalFunction::FIRST_ONLY(),
					'last-only' => DuplicateRemovalFunction::LAST_ONLY(),
					default => DuplicateRemovalFunction::RETAIN_ALL(),
				};
				return new SimpleMetaStackDefinition(StandardStackElements::parseList(LuckPerms::getInstance(), $format), $duplicateRemovalFunction, $startSpacer, $middleSpacer, $endSpacer);
			})());
		self::register("suffix_formatting_options", (function() use($config) : MetaStackDefinition {
				$format = $config->getNested('meta-formatting.suffix.format', []);
				if(count($format) < 1)
					$format[] = 'highest';
				$startSpacer = $config->getNested('meta-formatting.suffix.start-spacer', '');
				$middleSpacer = $config->getNested('meta-formatting.suffix.middle-spacer', ' ');
				$endSpacer = $config->getNested('meta-formatting.suffix.end-spacer', '');
				$duplicateRemovalFunction = match($config->getNested('meta-formatting.suffix.duplicates', '')) {
					'first-only' => DuplicateRemovalFunction::FIRST_ONLY(),
					'last-only' => DuplicateRemovalFunction::LAST_ONLY(),
					default => DuplicateRemovalFunction::RETAIN_ALL(),
				};
				return new SimpleMetaStackDefinition(StandardStackElements::parseList(LuckPerms::getInstance(), $format), $duplicateRemovalFunction, $startSpacer, $middleSpacer, $endSpacer);
			})());
		self::register("log_notify", $config->get('log-notify', true));
		self::register("log_notify_filtered_descriptions", (function() use($config) : array {
				$regexList = $config->get('log-notify-filtered-descriptions', []);
				return array_filter($regexList, function(string $value) : bool {
					return preg_match($value, '') === false;
				});
			})());
		self::register("auto_install_translations", $config->get('auto-install-translations', true));
		self::register("auto_op", $config->get('auto-op', false));
		self::register("ops_enabled", !$config->get('auto-op', false) and $config->get('enable-ops', true));
		self::register("commands_allow_op", $config->get('commands-allow-op', true));
		self::register("vault_unsafe_lookups", $config->get('vault-unsafe-lookups', false));
		self::register("vault_group_use_displaynames", $config->get('vault-group-use-displaynames', true));
		self::register("vault_npc_groups", $config->get('vault-npc-group', 'default'));
		self::register("vault_npc_op_status", $config->get('vault-npc-op-status', false));
		self::register("use_vault_server", $config->get('use-vault-server', false));
		self::register("vault_server", $config->get('use-vault-server', false) ? $config->get('vault-server', 'global') : $config->get('server', 'global'));
		self::register("vault_including_global", $config->get('vault-include-global', true));
		self::register("vault_ignore_world", $config->get('vault-ignore-world', false));
		self::register("fabric_integrated_server_owner_bypasss_checks", $config->get('integrated-server-owner-bypasses-checks', true));
		self::register("world_rewrites", array_map(function(string $key, string $value) {
				return [strtolower($key), strtolower($value)];
			}, array_keys($array = $config->get('world-rewrite', [])), array_values($array)));
		self::register("group_name_rewrites", $config->get('group-name-rewrite', [])); //TODO
		self::register("database_values", new StorageCredentials(
				$config->getNested('data.address', null),
				$config->getNested('data.database', null),
				$config->getNested('data.username', null),
				$config->getNested('data.password', null),
				($maxPoolSize = $config->getNested('data.pool-settings.maximum-pool-size', $config->getNested('data.pool-size', 10))),
				$config->getNested('data.pool-settings.minimum-idle', $maxPoolSize),
				$config->getNested('data.pool-settings.maximum-lifetime', 1800000),
				$config->getNested('data.pool-settings.keepalive-time', 0),
				$config->getNested('data.pool-settings.connection-timeout', 5000),
				$config->getNested('data.pool-settings.properties', []),
			));
		self::register("sql_table_prefix", $config->getNested('data.table-prefix', $config->getNested('data.table_prefix', 'luckperms_')));
		self::register("mongodb_collection_prefix", $config->getNested('data.mongodb-collection-prefix', $config->getNested('data.mongodb_collection_prefix', '')));
		self::register("mongodb_connection_uri", $config->getNested('data.mongodb-connection-uri', $config->getNested('data.mongodb_connection_uri', '')));
		self::register("storage_method", StorageType::parse($config->get('storage-method', 'sqlite'), StorageType::SQLITE()));
		self::register("watch_files", $config->get('watch-files', true));
		self::register("split_storage", $config->getNested('split-storage.enabled', false));
		self::register("split_storage_options", [
				'user' => StorageType::parse($config->getNested('split-storage.methods.user', 'sqlite'), StorageType::SQLITE()),
				'group' => StorageType::parse($config->getNested('split-storage.methods.group', 'sqlite'), StorageType::SQLITE()),
				'track' => StorageType::parse($config->getNested('split-storage.methods.track', 'sqlite'), StorageType::SQLITE()),
				'uuid' => StorageType::parse($config->getNested('split-storage.methods.uuid', 'sqlite'), StorageType::SQLITE()),
				'log' => StorageType::parse($config->getNested('split-storage.methods.log', 'sqlite'), StorageType::SQLITE()),
			]);
		self::register("messaging_service", $config->get('messaging-service', 'auto'));
		self::register("auto_push_updates", $config->get('auto-push-updates', true));
		self::register("push_log_entries", $config->get('push-log-entries', true));
		self::register("broadcast_received_log_entries", $config->get('broadcast-received-log-entries', false));
		self::register("redis_enabled", $config->getNested('redis.enabled', false));
		self::register("redis_address", $config->getNested('redis.address', null));
		self::register("redis_password", $config->getNested('redis.password', ''));
		self::register("redis_ssl", $config->getNested('redis.ssl', false));
		self::register("rabbitmq_enabled", $config->getNested('rabbitmq.enabled', false));
		self::register("rabbitmq_address", $config->getNested('rabbitmq.address', false));
		self::register("rabbitmq_virtual_host", $config->getNested('rabbitmq.vhost', '/'));
		self::register("rabbitmq_username", $config->getNested('rabbitmq.username', 'guest'));
		self::register("rabbitmq_password", $config->getNested('rabbitmq.password', 'guest'));
		self::register("bytebin_url", $config->get('bytebin-url', 'https://bytebin.lucko.me/'));
		self::register("web_editor_url_pattern", $config->get('web-editor-url', 'https://luckperms.net/editor/'));
		self::register("verbose_viewer_url_pattern", $config->get('verbose-viewer-url', 'https://luckperms.net/verbose/'));
		self::register("tree_viewer_url_pattern", $config->get('tree-viewer-url', 'https://luckperms.net/treeview/'));
	}
}