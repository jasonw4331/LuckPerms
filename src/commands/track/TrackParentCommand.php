<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\commands\track;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\StringEnumArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class TrackParentCommand extends BaseSubCommand{

	protected function prepare() : void{
		$this->registerArgument(0, new RawStringArgument('track', false)); // TODO: track name only

		$this->registerArgument(1, new class('insert', false) extends StringEnumArgument{
			protected const VALUES = ['insert' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new RawStringArgument('group', false));
		$this->registerArgument(3, new IntegerArgument('position', false));

		$this->registerArgument(1, new class('append', false) extends StringEnumArgument{
			protected const VALUES = ['append' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new RawStringArgument('group', false));

		$this->registerArgument(1, new class('remove', false) extends StringEnumArgument{
			protected const VALUES = ['remove' => true];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'subcommand';
			}
		});
		$this->registerArgument(2, new RawStringArgument('group', false));

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
			protected const VALUES = ['info' => true, 'editor' => true, 'clear' => true];
			public function parse(string $argument, CommandSender $sender) : string{
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
