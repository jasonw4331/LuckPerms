<?php
declare(strict_types=1);
namespace jasonwynn10\LuckPerms\metastacking;

use jasonwynn10\LuckPerms\api\metastacking\MetaStackElement;
use jasonwynn10\LuckPerms\LuckPerms;

/**
 * @generate-registry-docblock
 */
final class StandardStackElements {
	public static function parseFromString(LuckPerms $plugin, string $s) : ?MetaStackElement {
		$s = strtolower($s);

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

	private static function parseParam(string $s, string $prefix) : ?string {
		if((0 === strncmp($s, $prefix, strlen($prefix))) and strlen($s) > strlen($prefix)) {
			return substr($s, strlen($prefix));
		}
		return null;
	}

	public static function parseList(LuckPerms $plugin, array $strings) : array {
		return array_filter(array_map(function($s) use($plugin) {
			$parsed = self::parseFromString($plugin, $s);
			if($parsed === null) {
				$plugin->getLogger()->warning('Unable to parse from: '.$s);
			}
			return $parsed;
		}, $strings));
	}

	// enum stuff

	// highest

	private static function highestFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement {
		return FluentMetaStackElement::builder('HighestPriorityOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new FromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function highestNotFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement {
		return FluentMetaStackElement::builder('HighestPriorityNotOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new NotFromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function highestFromGroup(string $groupName) : MetaStackElement {
		return MetaStackElement::builder('HighestPriorityFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new FromGroupCheck($groupName))
			->build();
	}

	private static function highestNotFromGroup(string $groupName) : MetaStackElement {
		return FluentMetaStackElement::builder('HighestPriorityNotFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::HIGHEST_CHECK())
			->with(new NotFromGroupCheck($groupName))
			->build();
	}

	// lowest

	private static function lowestFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement {
		return FluentMetaStackElement::builder('LowestPriorityOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new FromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function lowestNotFromGroupOnTrack(LuckPerms $plugin, string $trackName) : MetaStackElement {
		return FluentMetaStackElement::builder('LowestPriorityNotOnTrack')
			->param('trackName', $trackName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new NotFromGroupOnTrackCheck($plugin, $trackName))
			->build();
	}

	private static function lowestFromGroup(string $groupName) : MetaStackElement {
		return MetaStackElement::builder('LowestPriorityFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new FromGroupCheck($groupName))
			->build();
	}

	private static function lowestNotFromGroup(string $groupName) : MetaStackElement {
		return FluentMetaStackElement::builder('LowestPriorityNotFromGroup')
			->param('groupName', $groupName)
			->with(self::TYPE_CHECK())
			->with(self::LOWEST_CHECK())
			->with(new NotFromGroupCheck($groupName))
			->build();
	}
}