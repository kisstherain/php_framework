<?php
namespace core;
require_once "Conf.php";
require_once "Controller.php";
require_once "Router.php";
require_once "Autoloader.php";
require_once "Util.php";
class Main {
	protected static $_instance = NULL;
	private function __clone(){}
	private function __construct() {}
	public function run($config){
		Conf::init($config);
		//设置app路由
		if($_SERVER['REQUEST_URI'] != "/"){
			$server_request_uri= parse_url($_SERVER['REQUEST_URI']);
			$domainInfo = Router::init()->parseDomain($server_request_uri['path'],Conf::init()->getConf("ROUTER"),Conf::init()->getConf("ROUTER")['default']);
			Conf::init()->setAppInfo($domainInfo);
			//注册应用地址
		}

		Autoloader::register(Conf::init()->getAppPath());
		//获取路由表
		$routers = Conf::init()->getRouter();
		//解析url路径
		$actArr = array();
		if($_SERVER['REQUEST_URI'] === "/"){
			$actArr = Router::init()->getDefaultAct();
		}else{
			$server_request_uri= parse_url($_SERVER['REQUEST_URI']);
			$actArr = Router::init()->parse($server_request_uri['path'],$routers);
		}
		Router::init()->load(Conf::init()->getAppPath(),$actArr);
	}
	public static function init(){
		if(null === self::$_instance){
			self::$_instance = new self;
		}
		return self::$_instance;
	}
}
