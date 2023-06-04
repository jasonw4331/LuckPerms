<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\scheduler;

interface SchedulerTask{

	function cancel() : void;

}
