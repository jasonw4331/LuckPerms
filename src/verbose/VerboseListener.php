<?php

declare(strict_types=1);

namespace jasonw4331\LuckPerms\verbose;

class VerboseListener{
	private CONST DATE_FORMAT = "yyyy-MM-dd HH:mm:ss z";

	// how much data should we store before stopping.
	private CONST DATA_TRUNCATION = 10000;
	// how many lines should we include in each stack trace send as a chat message
	private CONST STACK_TRUNCATION_CHAT = 5;
	// how many lines should we include in each stack trace in the web output
	private CONST STACK_TRUNCATION_WEB = 40;

}
