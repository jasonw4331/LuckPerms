<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

use pocketmine\utils\RegistryUtils;

if(count($argv) !== 2){
	die("Provide a path to process");
}

require dirname(__DIR__) . '/../../vendor/autoload.php'; // should be valid assuming folder plugin
require dirname(__DIR__) . '/vendor/autoload.php';

foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($argv[1], \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME)) as $file){
	if(substr($file, -4) !== ".php"){
		echo 'Exit -1'.PHP_EOL;
		continue;
	}
	echo $file.PHP_EOL;
	$contents = file_get_contents($file);
	if($contents === false){
		throw new \RuntimeException("Failed to get contents of $file");
	}

	if(preg_match("/^namespace (.+);$/m", $contents, $matches) !== 1 || preg_match('/^((final|abstract)\s+)?class /m', $contents) !== 1){
		echo 'Exit 0'.PHP_EOL;
		continue;
	}
	$shortClassName = basename($file, ".php");
	$className = $matches[1] . "\\" . $shortClassName;
	echo $className.PHP_EOL;
	if(!class_exists($className)){
		echo 'Exit ~1'.PHP_EOL;
		if(strpos($className, 'FlagUtil') !== false)
			continue;
		if(strpos($className, 'ConfigKeys') !== false)
			continue;
		if(strpos($className, 'AbstractContextSet') !== false)
			continue;
		if(strpos($className, 'ImmutableContextSetImpl') !== false)
			continue;
		if(strpos($className, 'QueryOptionsBuilderImpl') !== false)
			continue;
		if(strpos($className, 'QueryOptionsImpl') !== false)
			continue;
		if(strpos($className, 'SimpleMetaValueSelector') !== false)
			continue;
		if(strpos($className, 'DepthFirstIterator') !== false)
			continue;
		if(strpos($className, 'MetaStackDefinition') !== false)
			continue;
		if(strpos($className, 'AllParentsByWeight') !== false)
			continue;
		if(strpos($className, 'ParentsByWeight') !== false)
			continue;
		if(strpos($className, 'ParentsByWeight') !== false)
			continue;
		require $file;

		if(!class_exists($className)){
			echo 'Exit 1'.PHP_EOL;
			continue;
		}
	}
	$reflect = new \ReflectionClass($className);
	$docComment = $reflect->getDocComment();
	if($docComment === false || (preg_match("/^\s*\*\s*\@see .+::_generateMethodAnnotations\(\)$/m", $docComment) !== 1 && preg_match("/^\s*\*\s*@generate-registry-docblock$/m", $docComment) !== 1)){
		echo 'Exit 2'.PHP_EOL;
		continue;
	}
	echo "Found registry in $file\n";

	$replacement = RegistryUtils::_generateMethodAnnotations($matches[1], $className::getAll());

	$newContents = str_replace($docComment, $replacement, $contents);
	if($newContents !== $contents){
		echo "Writing changed file $file\n";
		file_put_contents($file, $newContents);
	}else{
		echo "No changes made to file $file\n";
	}
}

