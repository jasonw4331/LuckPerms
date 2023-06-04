<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\treeview;

use Ramsey\Collection\Map\TypedMap;

class ImmutableTreeNode{
	/** @var TypedMap<string, ImmutableTreeNode>|null $children */
	private ?TypedMap $children;

	public function __construct(?TypedMap $children){
		if($children !== null && $children->getValueType() === ImmutableTreeNode::class){
			$sortedMap = [];
			foreach($children->toArray() as $k1 => $o1){
				foreach($children->toArray() as $k2 => $o2){
					// sort first by if the node has any children
					$childStatus = $o1->compareTo($o2);
					if($childStatus < 0){
						$sortedMap[$k2] = $o2;
						continue;
					}elseif($childStatus > 0){
						$sortedMap[$k1] = $o1;
						continue;
					}

					// then sort alphabetically
					if(\strcasecmp($k1, $k2) > 0){
						$sortedMap[$k2] = $o2;
					}else{
						$sortedMap[$k1] = $o1;
					}
				}
			}

			$this->children = new TypedMap("string", ImmutableTreeNode::class, $sortedMap);
		}
	}

	public function getChildren() : ?TypedMap{
		return clone $this->children;
	}

	public function getNodeEndings() : array{
		if($this->children === null){
			return [];
		}

		$results = [];
		foreach($this->children as $value => $node){
			// add self
			$results[] = $value;

			// add child nodes, incrementing their level & appending their prefix node
			foreach($node->getNodeEndings() as $key => $childNode){
				$results[$key + 1] = $value . "." . $childNode;
			}
		}

		return $results;
	}

	public function toJson(string $prefix) : \stdClass{
		if($this->children === null){
			return (object) [];
		}

		$object = new \stdClass();
		foreach($this->children as $key => $value){
			$object->{$key} = $value->toJson($prefix . $key . ".");
		}
		return $object;
	}

	public function compareTo(ImmutableTreeNode $o) : int{
		return ($this->children !== null) === ($o->getChildren() !== null) ? 0 : ($this->children !== null ? 1 : -1);
	}
}
