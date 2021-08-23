<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\configs;

use jasonwynn10\LuckPerms\LuckPerms;
use jasonwynn10\LuckPerms\query\Flag;
use pocketmine\utils\EnumTrait;

final class ConfigKeys{
	use EnumTrait {
		__construct as Enum___construct;
	}

	private $value;

	protected static function setup() : void{
		self::registerAll(
			new self("server", LuckPerms::getInstance()->getConfig()->get('server', 'global')),
			new self("sync_time", LuckPerms::getInstance()->getConfig()->get('sync-minutes', -1)),
			new self("global_query_options", (function() : QueryOptions {
				$flags = [];
				if(LuckPerms::getInstance()->getConfig()->get('include-global', true)) {
					$flags[] = Flag::INCLUDE_NODES_WITHOUT_SERVER_CONTEXT();
				}
				if(LuckPerms::getInstance()->getConfig()->get('include-global-world', true)) {
					$flags[] = Flag::INCLUDE_NODES_WITHOUT_WORLD_CONTEXT();
				}
				if(LuckPerms::getInstance()->getConfig()->get('apply-global-groups', true)) {
					$flags[] = Flag::APPLY_INHERITANCE_NODES_WITHOUT_SERVER_CONTEXT();
				}
				if(LuckPerms::getInstance()->getConfig()->get('apply-global-world-groups', true)) {
					$flags[] = Flag::APPLY_INHERITANCE_NODES_WITHOUT_WORLD_CONTEXT();
				}

				return (new QueryOptionsBuilderImpl(QueryMode::CONTEXTUAL))->flags($flags)->build();
			})()),
			new self("context_satisfy_mode", LuckPerms::getInstance()->getConfig()->get('context-satisfy-mode', 'at-least-one-value-per-key') === 'all-values-per-key' ? ContextSatisfyMode::ALL_VALUES_PER_KEY() : ContextSatisfyMode::AT_LEAST_ONE_VALUE_PER_KEY()),
			new self("use_server_uuid_cache", LuckPerms::getInstance()->getConfig()->get('use-server-uuid-cache', false)),
			new self("allow_invalid_usernames", LuckPerms::getInstance()->getConfig()->get('allow-invalid-usernames', false)),
			new self("skip_bulkupdate_confirmation", LuckPerms::getInstance()->getConfig()->get('skip-bulkupdate-confirmation', false)),
			new self("debug_logins", LuckPerms::getInstance()->getConfig()->get('debug-logins', false)),
			new self("cancel_failed_logins", LuckPerms::getInstance()->getConfig()->get('cancel-failed-logins', false)),
			new self("update_client_command_list", LuckPerms::getInstance()->getConfig()->get('update-client-command-list', true)),
			new self("register_command_list_data", LuckPerms::getInstance()->getConfig()->get('register-command-list-data', true)),
			new self("resolve_command_selectors", LuckPerms::getInstance()->getConfig()->get('resolve-command-selectors', false)),
			new self("temporary_add_behaviour", match (LuckPerms::getInstance()->getConfig()->get('temporary-add-behaviour', 'deny')) {
				'accumulate' => TemporaryNodeMergeStrategy::ADD_NEW_DURATION_TO_EXISTING(),
				'replace' => TemporaryNodeMergeStrategy::REPLACE_EXISTING_IF_DURATION_LONGER(),
				default => TemporaryNodeMergeStrategy::NONE(),
			}),
			new self("primary_group_calculation_method", (function() : string {
				$option = LuckPerms::getInstance()->getConfig()->get('primary-group-calculation', 'stored');
				if($option !== 'stored' and $option !== 'parents-by-weight' and $option !== 'all-parents-by-weight')
					$option = 'stored';
				return $option;
			})()),
			new self("primary_group_calculation", match(LuckPerms::getInstance()->getConfig()->get('primary-group-calculation', 'stored')) {
				default => new Stored(),
				'parents-by-weight' => new ParentsByWeight(),
				'all-parents-by-weight' => new AllParentsByWeight(),
			}),
			new self("prevent_primary_group_removal", LuckPerms::getInstance()->getConfig()->get('prevent-primary-group-removal', true)),
			new self("use_argument_based_command_permissions", LuckPerms::getInstance()->getConfig()->get('argument-based-command-permissions', false)),
			new self("require_sender_group_membership_to_modify", LuckPerms::getInstance()->getConfig()->get('require-sender-group-membership-to-modify', false)),
			new self("applying_wildcards", LuckPerms::getInstance()->getConfig()->get('apply-wildcards', false)),
			new self("applying_wildcards_sponge", false),
			new self("apply_default_negations_before_wildcards", LuckPerms::getInstance()->getConfig()->get('apply-default-negated-permissions-before-wildcards', false)),
			new self("applying_regex", LuckPerms::getInstance()->getConfig()->get('apply-regex', true)),
			new self("applying_shorthand", LuckPerms::getInstance()->getConfig()->get('apply-shorthand', true)),
			new self("apply_bukkit_child_permissions", LuckPerms::getInstance()->getConfig()->get('apply-bukkit-child-permissions', true)),
			new self("apply_bukkit_default_permissions", LuckPerms::getInstance()->getConfig()->get('apply-bukkit-default-permissions', true)),
			new self("apply_bukkit_attachment_permissions", LuckPerms::getInstance()->getConfig()->get('apply-bukkit-attachment-permissions', true)),
			new self("apply_nukkit_child_permissions", LuckPerms::getInstance()->getConfig()->get('apply-nukkit-child-permissions', true)),
			new self("apply_nukkit_default_permissions", LuckPerms::getInstance()->getConfig()->get('apply-nukkit-default-permissions', true)),
			new self("apply_nukkit_attachment_permissions", LuckPerms::getInstance()->getConfig()->get('apply-nukkit-attachment-permissions', true)),
			new self("apply_bungee_config_permissions", LuckPerms::getInstance()->getConfig()->get('apply-bungee-config-permissions', false)),
			new self("apply_sponge_default_subjects", LuckPerms::getInstance()->getConfig()->get('apply-sponge-default-subjects', true)),
			new self("inheritance_traversal_algorithm", match(LuckPerms::getInstance()->getConfig()->get('inheritance-traversal-algorithm', 'depth-first-pre-order')) {
				'breadth-first' => TraversalAlgorithm::BREADTH_FIRST(),
				'depth-first-post-order' => TraversalAlgorithm::DEPTH_FIRST_POST_ORDER(),
				default => TraversalAlgorithm::DEPTH_FIRST_PRE_ORDER(),
			}),
			new self("post_traversal_inheritance_sort", LuckPerms::getInstance()->getConfig()->get('post-traversal-inheritance-sort', false)),
			new self("meta_value_selector", (function() {
				$defaultStrategy = match(LuckPerms::getInstance()->getConfig()->get('meta-value-selection-default', 'inheritance')) {
					'inheritance' => Strategy::INHERITANCE(),
					'highest_number' => Strategy::HIGHEST_NUMBER(),
					'lowest_number' => Strategy::LOWEST_NUMBER(),
				};
				$strategies = LuckPerms::getInstance()->getConfig()->get('meta-value-selection', []);
				$strategies = array_filter(array_map(function($key, $value) {
					$fn = match($value) {
						'inheritance' => Strategy::INHERITANCE(),
						'highest_number' => Strategy::HIGHEST_NUMBER(),
						'lowest_number' => Strategy::LOWEST_NUMBER(),
					};
					return $fn($key);
				}, array_keys($strategies), array_values($strategies)));
				return new SimpleMetaValueSelector($strategies, $defaultStrategy);
			})()),
			new self("group_weights", (function() : array {
				$weights = LuckPerms::getInstance()->getConfig()->get('group-weight', []);
				return array_map(function($key, $value) {
					return [strtolower($key), intval($value)];
				}, array_keys($weights), array_values($weights));
			})()),
			new self("prefix_formatting_options", (function() : MetaStackDefinition {
				$config = LuckPerms::getInstance()->getConfig();
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
			})()),
			new self("suffix_formatting_options", (function() : MetaStackDefinition {
				$config = LuckPerms::getInstance()->getConfig();
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
			})()),
			new self("log_notify", LuckPerms::getInstance()->getConfig()->get('log-notify', true)),
			new self("log_notify_filtered_descriptions", (function(){
				$regexList = LuckPerms::getInstance()->getConfig()->get('log-notify-filtered-descriptions', []);
				return array_filter($regexList, function(string $value) : bool {
					return preg_match($value, '') === false;
				});
			})()),
			new self("auto_install_translations", LuckPerms::getInstance()->getConfig()->get('auto-install-translations', true)),
			new self("auto_op", LuckPerms::getInstance()->getConfig()->get('auto-op', false)),
			new self("ops_enabled", !LuckPerms::getInstance()->getConfig()->get('auto-op', false) and LuckPerms::getInstance()->getConfig()->get('enable-ops', true)),
			new self("commands_allow_op", LuckPerms::getInstance()->getConfig()->get('commands-allow-op', true)),
			new self("vault_unsafe_lookups", LuckPerms::getInstance()->getConfig()->get('vault-unsafe-lookups', false)),
			new self("vault_group_use_displaynames", LuckPerms::getInstance()->getConfig()->get('vault-group-use-displaynames', true)),
			new self("vault_npc_groups", LuckPerms::getInstance()->getConfig()->get('vault-npc-group', 'default')),
			new self("vault_npc_op_status", LuckPerms::getInstance()->getConfig()->get('vault-npc-op-status', false)),
			new self("use_vault_server", LuckPerms::getInstance()->getConfig()->get('use-vault-server', false)),
			new self("vault_server", LuckPerms::getInstance()->getConfig()->get('use-vault-server', false) ? LuckPerms::getInstance()->getConfig()->get('vault-server', 'global') : LuckPerms::getInstance()->getConfig()->get('server', 'global')),
			new self("vault_including_global", LuckPerms::getInstance()->getConfig()->get('vault-include-global', true)),
			new self("vault_ignore_world", LuckPerms::getInstance()->getConfig()->get('vault-ignore-world', false)),
			new self("fabric_integrated_server_owner_bypasss_checks", LuckPerms::getInstance()->getConfig()->get('integrated-server-owner-bypasses-checks', true)),
			new self("world_rewrites", array_map(function(string $key, string $value) {
				return [strtolower($key), strtolower($value)];
			}, array_keys($array = LuckPerms::getInstance()->getConfig()->get('world-rewrite', [])), array_values($array))),
			new self("group_name_rewrites", LuckPerms::getInstance()->getConfig()->get('group-name-rewrite', [])), //TODO
			new self("database_values", new StorageCredentials(
				LuckPerms::getInstance()->getConfig()->getNested('data.address', null),
				LuckPerms::getInstance()->getConfig()->getNested('data.database', null),
				LuckPerms::getInstance()->getConfig()->getNested('data.username', null),
				LuckPerms::getInstance()->getConfig()->getNested('data.password', null),
				($maxPoolSize = LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.maximum-pool-size', LuckPerms::getInstance()->getConfig()->getNested('data.pool-size', 10))),
				LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.minimum-idle', $maxPoolSize),
				LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.maximum-lifetime', 1800000),
				LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.keepalive-time', 0),
				LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.connection-timeout', 5000),
				LuckPerms::getInstance()->getConfig()->getNested('data.pool-settings.properties', []),
			)),
			new self("sql_table_prefix", LuckPerms::getInstance()->getConfig()->getNested('data.table-prefix', LuckPerms::getInstance()->getConfig()->getNested('data.table_prefix', 'luckperms_'))),
			new self("mongodb_collection_prefix", LuckPerms::getInstance()->getConfig()->getNested('data.mongodb-collection-prefix', LuckPerms::getInstance()->getConfig()->getNested('data.mongodb_collection_prefix', ''))),
			new self("mongodb_connection_uri", LuckPerms::getInstance()->getConfig()->getNested('data.mongodb-connection-uri', LuckPerms::getInstance()->getConfig()->getNested('data.mongodb_connection_uri', ''))),
			new self("storage_method", StorageType::parse(LuckPerms::getInstance()->getConfig()->get('storage-method', 'sqlite'), StorageType::SQLITE())),
			new self("watch_files", LuckPerms::getInstance()->getConfig()->get('watch-files', true)),
			new self("split_storage", LuckPerms::getInstance()->getConfig()->getNested('split-storage.enabled', false)),
			new self("split_storage_options", [
				'user' => StorageType::parse(LuckPerms::getInstance()->getConfig()->getNested('split-storage.methods.user', 'sqlite'), StorageType::SQLITE()),
				'group' => StorageType::parse(LuckPerms::getInstance()->getConfig()->getNested('split-storage.methods.group', 'sqlite'), StorageType::SQLITE()),
				'track' => StorageType::parse(LuckPerms::getInstance()->getConfig()->getNested('split-storage.methods.track', 'sqlite'), StorageType::SQLITE()),
				'uuid' => StorageType::parse(LuckPerms::getInstance()->getConfig()->getNested('split-storage.methods.uuid', 'sqlite'), StorageType::SQLITE()),
				'log' => StorageType::parse(LuckPerms::getInstance()->getConfig()->getNested('split-storage.methods.log', 'sqlite'), StorageType::SQLITE()),
			]),
			new self("messaging_service", LuckPerms::getInstance()->getConfig()->get('messaging-service', 'auto')),
			new self("auto_push_updates", LuckPerms::getInstance()->getConfig()->get('auto-push-updates', true)),
			new self("push_log_entries", LuckPerms::getInstance()->getConfig()->get('push-log-entries', true)),
			new self("broadcast_received_log_entries", LuckPerms::getInstance()->getConfig()->get('broadcast-received-log-entries', false)),
			new self("redis_enabled", LuckPerms::getInstance()->getConfig()->getNested('redis.enabled', false)),
			new self("redis_address", LuckPerms::getInstance()->getConfig()->getNested('redis.address', null)),
			new self("redis_password", LuckPerms::getInstance()->getConfig()->getNested('redis.password', '')),
			new self("redis_ssl", LuckPerms::getInstance()->getConfig()->getNested('redis.ssl', false)),
			new self("rabbitmq_enabled", LuckPerms::getInstance()->getConfig()->getNested('rabbitmq.enabled', false)),
			new self("rabbitmq_address", LuckPerms::getInstance()->getConfig()->getNested('rabbitmq.address', false)),
			new self("rabbitmq_virtual_host", LuckPerms::getInstance()->getConfig()->getNested('rabbitmq.vhost', '/')),
			new self("rabbitmq_username", LuckPerms::getInstance()->getConfig()->getNested('rabbitmq.username', 'guest')),
			new self("rabbitmq_password", LuckPerms::getInstance()->getConfig()->getNested('rabbitmq.password', 'guest')),
			new self("bytebin_url", LuckPerms::getInstance()->getConfig()->get('bytebin-url', 'https://bytebin.lucko.me/')),
			new self("web_editor_url_pattern", LuckPerms::getInstance()->getConfig()->get('web-editor-url', 'https://luckperms.net/editor/')),
			new self("verbose_viewer_url_pattern", LuckPerms::getInstance()->getConfig()->get('verbose-viewer-url', 'https://luckperms.net/verbose/')),
			new self("tree_viewer_url_pattern", LuckPerms::getInstance()->getConfig()->get('tree-viewer-url', 'https://luckperms.net/treeview/')),
		);
		self::register(new self("keys", self::getAll()));
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	private function __construct(string $key, $value){
		$this->Enum___construct($key);
		$this->value = $value;
	}

	public function getKeyValue() { return $this->value; }

	public static function getKeys() : array {
		return self::KEYS();
	}
}