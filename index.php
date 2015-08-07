<?php
	ini_set("display_errors","On");
	error_reporting(E_ALL);
	require_once "lib/LibManager.php";
	//加载框架管理类
	require_once "core/Main.php";
	//运行框架
	core\Main::init()->run(require("config.php"));

