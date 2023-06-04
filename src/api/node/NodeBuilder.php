<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\api\node;

use jasonw4331\LuckPerms\api\node\metadata\NodeMetadataKey;
use jasonw4331\LuckPerms\context\ContextSet;

abstract class NodeBuilder{

	abstract function value(bool $value) : NodeBuilder;

	abstract function negated(bool $negated) : NodeBuilder;

	abstract function expiry(int $expiryEpochSeconds) : NodeBuilder;

	abstract function clearExpiry() : NodeBuilder;

	abstract function context(ContextSet $contextSet) : NodeBuilder;

	abstract function withContext(string|ContextSet $keyOrContextSet, string $value) : NodeBuilder;

	abstract function withMetadata(NodeMetadataKey $key, mixed $metadata) : NodeBuilder;

	abstract function build() : ScopedNode;

}
