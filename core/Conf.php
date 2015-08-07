<?php
namespace core;
class Conf{
	private static $_instance = NULL;
	private $_config = array();
	//载入配置信息
	private function __construct($config){
		if(is_array($config)){
			$this->_config= $config;
		}
		$this->_initErrorReport();
	}
	//初始化错误信息显示
	private function _initErrorReport(){
		switch($this->getEnviroment()){
			case "development":
				ini_set("display_errors","On");
				error_reporting(E_ALL);
			break;
			case "production":
				error_reporting(0);
			break;
			default:
				error_reporting(0);
			break;
		}
	}
	private function __clone(){}
	public static function init($config = null){
		if(null !== self::$_instance){
			return self::$_instance;
		}
		self::$_instance = new self($config);
		return self::$_instance;
	}
	public function getConf($key,$default = false){
		if(is_array($this->_config) && isset($this->_config[$key])){
			return $this->_config[$key];
		}else{
			return $default;
		}
	}
	public function getEnviroment(){
		return $this->getConf("ENVIRONMENT","production");
	}
    private $appInfo = array();
    public function setAppInfo($appInfo= ""){
        $this->appInfo= $appInfo;
    }
	public function getRouter($key = ""){
        if(!empty($this->appInfo)){
            return $this->appInfo['router'];
        }
		$key = empty($key)?"default":$key;
		if(array_key_exists("ROUTER",$this->_config)){
			return isset($this->_config['ROUTER'][$key]) && isset($this->_config['ROUTER'][$key]['router'])
				?$this->_config['ROUTER'][$key]['router']
				:array();
		}
		return array();
	}
	public function getAppPath($key = ""){
        if(!empty($this->appInfo)){
            return $this->appInfo['path'];
        }
		$key = empty($key)?"default":$key;
		if(array_key_exists("ROUTER",$this->_config)){
			return isset($this->_config['ROUTER'][$key]) && isset($this->_config['ROUTER'][$key]['path'])
				?$this->_config['ROUTER'][$key]['path']
				:"";
		}
		return "";
	}
}
