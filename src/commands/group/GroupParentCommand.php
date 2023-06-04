<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\commands\group;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\StringEnumArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class GroupParentCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->registerArgument(0, new RawStringArgument('user', false)); // TODO: group name only

		$this->registerArgument(1, new class('listmembers', false) extends StringEnumArgument{
			protected const VALUES = ['listmembers' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new IntegerArgument('page', true));

		$this->registerArgument(1, new class('setweight', false) extends StringEnumArgument{
			protected const VALUES = ['setweight' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new IntegerArgument('weight', false));

		$this->registerArgument(1, new class('setdisplayname', false) extends StringEnumArgument{
			protected const VALUES = ['setdisplayname' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new IntegerArgument('name', false));

		$this->registerArgument(1, new class('clear', false) extends StringEnumArgument{
			protected const VALUES = ['clear' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new TextArgument('context', true));

		$this->registerArgument(1, new class('rename', false) extends StringEnumArgument{
			protected const VALUES = ['rename' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new RawStringArgument('new name', false));

		$this->registerArgument(1, new class('clone', false) extends StringEnumArgument{
			protected const VALUES = ['clone' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new RawStringArgument('name of clone', false));

		$this->registerArgument(1, new class('subcommand', false) extends StringEnumArgument{
			protected const VALUES = ['info' => true, 'permission' => true, 'parent' => true, 'meta' => true, 'editor' => true, 'showtracks' => true];
			public function parse(string $argument, CommandSender $sender) : mixed{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		// TODO: Implement onRun() method.
	}
}
