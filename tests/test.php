<?php

	require_once __DIR__.'/../vendor/autoload.php';

	use Quincykwende\PrintfulCache\FileCache;
	use Quincykwende\PrintfulCache\File;

	$dd = new FileCache();

	//$file = new File();

	//var_dump($dd->set("ss", "dddd", 60));

	var_dump($dd->get("ss"));



