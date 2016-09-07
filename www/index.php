<?php
	error_reporting(E_ALL & ~E_WARNING & ~E_STRICT);
	define('APPS_PATH', __DIR__);
	spl_autoload_register(function ($class) {
		require_once (APPS_PATH . '/php/classes/' . $class . '.php');
	});
	new CodeVote;
?>