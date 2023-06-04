<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\MetaStackElement;
use jasonwynn10\LuckPerms\api\node\ChatMetaType;
use jasonwynn10\LuckPerms\api\node\types\ChatMetaNode;
use jasonwynn10\LuckPerms\LuckPerms;
use pocketmine\utils\CloningRegistryTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static FluentMetaStackElement HIGHEST()
 * @method static StackElementUtility HIGHEST_CHECK()
 * @method static FluentMetaStackElement HIGHEST_INHERITED()
 * @method static FluentMetaStackElement HIGHEST_OWN()
 * @method static StackElementUtility INHERITED_CHECK()
 * @method static FluentMetaStackElement LOWEST()
 * @method static StackElementUtility LOWEST_CHECK()
 * @method static FluentMetaStackElement LOWEST_INHERITED()
 * @method static FluentMetaStackElement LOWEST_OWN()
 * @method static StackElementUtility OWN_CHECK()
 * @method static StackElementUtility TYPE_CHECK()
 */
final class StandardStackElements{
	use CloningRegistryTrait;

	private function __construct(){
		//NOOP
	}

	protected static function register(string $name, MetaStackElement $element) : void{
		self::_registryRegister($name, $element);
	}

	/**
	 * @return MetaStackElement[]
	 * @phpstan-return array<string, MetaStackElement>
	 */
	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var MetaStackElement[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		// utility functions, used in combination with FluentMetaStackElement for form full MetaStackElements
		self::register("type_check", new StackElementUtility(static fn(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) => $type->nodeType()->matches($node)));
		self::register("highest_check", new StackElementUtility(static fn(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) => $node->getPriority() > $current->getPriority()));
		self::register("lowest_check", new StackElementUtility(static fn(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) => $node->getPriority() < $current->getPriority()));
		self::register("own_check", new StackElementUtility(static fn(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) => $node->metadata(InheritanceOriginMetadata::KEY)->getOrigin()->getType()->equals(PermissionHolder::Identifier->USER_TYPE)));
		self::register("inherited_check", new StackElementUtility(static fn(ChatMetaType $type, ChatMetaNode $node, ChatMetaNode $current) => $node->metadata(InheritanceOriginMetadata::KEY)->getOrigin()->getType()->equals(PermissionHolder::Identifier->GROUP_TYPE)));
		// implementations
		self::register("highest", (new FluentMetaStackElementBuilder("highest"))->with(self::HIGHEST_CHECK())->build());
		self::register("highest_own", (new FluentMetaStackElementBuilder("highest_own"))->with(self::HIGHEST_CHECK())->with(self::OWN_CHECK())->build());
		self::register("highest_inherited", (new FluentMetaStackElementBuilder("highest_inherited"))->with(self::HIGHEST_CHECK())->with(self::INHERITED_CHECK())->build());
		self::register("lowest", (new FluentMetaStackElementBuilder("lowest"))->with(self::LOWEST_CHECK())->build());
		self::register("lowest_own", (new FluentMetaStackElementBuilder("lowest_own"))->with(self::LOWEST_CHECK())->with(self::OWN_CHECK())->build());
		self::register("lowest_inherited", (new FluentMetaStackElementBuilder("lowest_inherited"))->with(self::LOWEST_CHECK())->with(self::INHERITED_CHECK())->build());
	}

	public static function parseFromString(LuckPerms $plugin, string $s) : ?MetaStackElement{
		$s = \strtolower($s);

		if($s === 'highest') return self::HIGHEST();
		if($s === 'lowest') return self::LOWEST();
		if($s === 'highest_own') return self::HIGHEST_OWN();
		if($s === 'lowest_own') return self::LOWEST_OWN();
		if($s === 'highest_inherited') return self::HIGHEST_INHERITED();
		if($s === 'lowest_inherited') return self::LOWEST_INHERITED();

		if(($p = self::parseParam($s, 'highest_on_track_')) !== null) return self::highestFromGroupOnTrack($plugin, $p);
		if(($p = self::parseParam($s, 'lowest_on_track_')) !== null) return self::lowestFromGroupOnTrack($plugin, $p);
		if(($p = self::parseParam($s, 'highest_not_on_track_')) !== null) return self::highestNotFromGroupOnTrack($plugin, $p);
		if(($p = self::parseParam($s, 'lowest_not_on_track_')) !== null) return self::lowestNotFromGroupOnTrack($plugin, $p);
		if(($p = self::parseParam($s, 'highest_from_group_')) !== null) return self::highestFromGroup($p);
		if(($p = self::parseParam($s, 'lowest_from_group_')) !== null) return self::lowestFromGroup($p);
		if(($p = self::parseParam($s, 'highest_not_from_group_')) !== null) return self::highestNotFromGroup($p);
		if(($p = self::parseParam($s, 'lowest_not_from_group_')) !== null) return self::lowestNotFromGroup($p);

		return null;
	}

	private static function parseParam(string $s, string $prefix) : ?string{
		if((0 === \strncmp($s, $prefix, \strlen($prefix))) && \strlen($s) > \strlen($prefix)){
			return \substr($s, \strlen($prefix));
		}
		return null;
	}

	public static function parseList(LuckPerms $plugin, array $strings) : array{
		return \array_filter(\array_map(function($s) use ($plugin){
			$parsed = self::parseFromString($plugin, $s);
			if($parsed === null){
				$plugin->getLogger()->warning('Unable to parse from: ' . $s);
			}
			return $parsed;
		}, $strings));
	}

	// enum stuff

	// highest

	private static function highestFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement{
		return FluentMetaStackElement::builder('HighestPriorityOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new FromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function highestNotFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement{
		return FluentMetaStackElement::builder('HighestPriorityNotOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new NotFromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function highestFromGroup(string $groupName) : MetaStackElement{
		return MetaStackElement::builder('HighestPriorityFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new FromGroupCheck($groupName))
			->build();
	}

	private static function highestNotFromGroup(string $groupName) : MetaStackElement{
		return FluentMetaStackElement::builder('HighestPriorityNotFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new NotFromGroupCheck($groupName))
			->build();
	}

	// lowest

	private static function lowestFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement{
		return FluentMetaStackElement::builder('LowestPriorityOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new FromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function lowestNotFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement{
		return FluentMetaStackElement::builder('LowestPriorityNotOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new NotFromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function lowestFromGroup(string $groupName) : MetaStackElement{
		return MetaStackElement::builder('LowestPriorityFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new FromGroupCheck($groupName))
			->build();
	}

	private static function lowestNotFromGroup(string $groupName) : MetaStackElement{
		return FluentMetaStackElement::builder('LowestPriorityNotFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new NotFromGroupCheck($groupName))
			->build();
	}
}
