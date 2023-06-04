<?php

declare(strict_types=1);

namespace jasonwynn10\LuckPerms\commands\user;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\StringEnumArgument;
use CortexPE\Commando\args\TargetArgument;
use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseSubCommand;
use jasonwynn10\LuckPerms\command\SingleValueEnum;
use pocketmine\command\CommandSender;

class UserParentCommand extends BaseSubCommand{

	/**
	 * Due to the way Minecraft accepts command arguments in the AvailableCommands packet, we have to register all the
	 * arguments by order of longest chain before shorter chains. Commando does not support subcommands within
	 * subcommands, so we have to do this manually.
	 *
	 * @throws \CortexPE\Commando\exception\ArgumentOrderException
	 */
	protected function prepare() : void{
		$this->setPermission('luckperms.user.info;luckperms.user.editor;luckperms.user.promote;luckperms.user.demote;luckperms.user.showtracks;luckperms.user.clear;luckperms.user.clone;luckperms.user.permission.info;luckperms.user.permission.set;luckperms.user.permission.unset;luckperms.user.permission.settemp;luckperms.user.permission.unsettemp;luckperms.user.permission.check;luckperms.user.permission.clear;luckperms.user.parent.info;luckperms.user.parent.set;luckperms.user.parent.add;luckperms.user.parent.remove;luckperms.user.parent.settrack;luckperms.user.parent.addtemp;luckperms.user.parent.removetemp;luckperms.user.parent.clear;luckperms.user.parent.cleartrack;luckperms.user.parent.switchprimarygroup;luckperms.user.meta.info;luckperms.user.meta.set;luckperms.user.meta.unset;luckperms.user.meta.settemp;luckperms.user.meta.unsettemp;luckperms.user.meta.addprefix;luckperms.user.meta.addsuffix;luckperms.user.meta.setprefix;luckperms.user.meta.setsuffix;luckperms.user.meta.removeprefix;luckperms.user.meta.removesuffix;luckperms.user.meta.addtempprefix;luckperms.user.meta.addtempsuffix;luckperms.user.meta.settempprefix;luckperms.user.meta.settempsuffix;luckperms.user.meta.removetempprefix;luckperms.user.meta.removetempsuffix;luckperms.user.meta.clear');

		$this->registerArgument(0, new TargetArgument('user', false)); // TODO: username or UUID only

		// 7 deep after user
		// /lp user <user> permission settemp <node> <true/false> <duration> [temporary modifier] [context...]
		$this->registerArgument(1, new SingleValueEnum('permission'));
		$this->registerArgument(2, new SingleValueEnum('settemp'));
		$this->registerArgument(3, new RawStringArgument('node', false));
		$this->registerArgument(4, new BooleanArgument('value', false));
		$this->registerArgument(5, new IntegerArgument('duration', false)); // TODO: change to date validation
		$this->registerArgument(6, new class('temporary modifier', false) extends StringEnumArgument{
			protected const VALUES = ['accumulate' => 'accumulate', 'replace' => 'replace', 'deny' => 'deny'];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'Temporary Modifier';
			}
		});
		$this->registerArgument(7, new TextArgument('context', true));

		// 7 deep after user
		// /lp user <user> meta settemp <key> <value> <duration> [temporary modifier] [context...]
		$this->registerArgument(1, new SingleValueEnum('meta'));
		$this->registerArgument(2, new SingleValueEnum('settemp'));
		$this->registerArgument(3, new RawStringArgument('key', false));
		$this->registerArgument(4, new RawStringArgument('value', false));
		$this->registerArgument(5, new IntegerArgument('duration', false)); // TODO: change to date validation
		$this->registerArgument(6, new class('temporary modifier', false) extends StringEnumArgument{
			protected const VALUES = ['accumulate' => 'accumulate', 'replace' => 'replace', 'deny' => 'deny'];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'Temporary Modifier';
			}
		});
		$this->registerArgument(7, new TextArgument('context', true));

		// 6 deep after user
		// /lp user <user> parent addtemp <group> <duration> [temporary modifier] [context...]
		$this->registerArgument(1, new SingleValueEnum('parent'));
		$this->registerArgument(2, new SingleValueEnum('addtemp'));
		$this->registerArgument(3, new RawStringArgument('group', false)); // TODO: group name validation
		$this->registerArgument(4, new IntegerArgument('duration', false)); // TODO: change to date validation
		$this->registerArgument(5, new class('temporary modifier', false) extends StringEnumArgument{
			protected const VALUES = ['accumulate' => 'accumulate', 'replace' => 'replace', 'deny' => 'deny'];
			public function parse(string $argument, CommandSender $sender) : string{
				return $argument;
			}
			public function getTypeName() : string{
				return 'Temporary Modifier';
			}
		});
		$this->registerArgument(6, new TextArgument('context', true));

		$this->registerArgument(1, new SingleValueEnum('promote'));
		$this->registerArgument(2, new RawStringArgument('track', false));
		$this->registerArgument(3, new TextArgument('context', true));

		$this->registerArgument(1, new SingleValueEnum('demote'));
		$this->registerArgument(2, new RawStringArgument('track', false));
		$this->registerArgument(3, new TextArgument('context', true));

		$this->registerArgument(1, new SingleValueEnum('clear'));
		$this->registerArgument(2, new TextArgument('context', true));

		$this->registerArgument(1, new SingleValueEnum('clone'));
		$this->registerArgument(2, new TargetArgument('user', false)); // TODO: username or UUID only

		$this->registerArgument(1, new class('subcommand', false) extends StringEnumArgument{
			protected const VALUES = ['info' => true, 'editor' => true, 'showtracks' => true];
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
