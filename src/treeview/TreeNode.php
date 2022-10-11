<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\treeview;

use Ramsey\Collection\Map\TypedMap;

class TreeNode{

	private static function allowInsert(TreeNode $node) : bool {
		// level 0    =>  no limit
		// level 1/2  =>  up to 500
		// level 3+   =>  up to 100

		if($node->level === 0) {
			return true;
		}elseif($node->level <= 2) {
			return $node->getChildrenSize() < 500;
		}else{
			return $node->getChildrenSize() < 100;
		}
	}

	/** @var TypedMap<string, TreeNode>|null $children */
	private ?TypedMap $children;
	private int $level;

	public function __construct(?TreeNode $parent = null) {
		$this->level = $parent?->level + 1 ?? 0;
	}

	// lazy init
	private function getChildMap() : TypedMap {
		if($this->children === null) {
			$this->children = new TypedMap("string", TreeNode::class);
		}
		return $this->children;
	}

	public function tryInsert(string $s) : ?TreeNode {
		$childMap = $this->getChildMap();
		if(!$this->allowInsert($this)) {
			return null;
		}
		return $childMap->putIfAbsent($s, new TreeNode($this));
	}

	public function getChildren() : ?TypedMap {
		return $this->children;
	}

	public function getChildrenSize() : int {
		return $this->children?->count() ?? 0;
	}

	public function makeImmutableCopy() : ImmutableTreeNode {
		$array = $this->children?->toArray() ?? [];
		array_walk($array, static fn(TreeNode $node) : ImmutableTreeNode => $node->makeImmutableCopy()); // use array walk to keep the keys
		return new ImmutableTreeNode(new TypedMap("string", ImmutableTreeNode::class, $array));
	}
}