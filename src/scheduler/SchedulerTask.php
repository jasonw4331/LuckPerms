<?php
declare(strict_types=1);

namespace jasonwynn10\LuckPerms\scheduler;

interface SchedulerTask{

	function cancel() : void;

}