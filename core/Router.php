<?php
namespace core;
class Router {
	private static $_instance = NULL;
	private $_params = array();
	private $_aPrefix = 'action_';
	private $_cPrefix = 'Controller_';
	private $_cFileName = "controller";
	private function __clone(){}
	private function __construct(){}
	public static function init(){
		if(null !== self::$_instance){
			return self::$_instance;
		}
		self::$_instance = new self;
		return self::$_instance;
	}
	public function getDefaultAct(){
		return $this->_cls_act();
	}
	public function parse($path , $routers ,$def = array()){
		foreach ($routers as $k => $v) {
			$reg = str_replace(array('/'), array('\/'), $k);
			$reg = preg_replace_callback(
				'#@([\w]+)(:([^/\(\)]*))?#',
				function($matches) use (&$ids) {
					$ids[$matches[1]] = null;
					if (isset($matches[3])) {
						return '(?P<'.$matches[1].'>'.$matches[3].')';
					}
					return '(?P<'.$matches[1].'>[^\/\?]+)';
				},$reg
				);
			if(preg_match('/^'.$reg.'$/',$path,$match)){
				return $this->_cls_act($v,$match);
			}
		}
		return NULL;
	}
	public function parseDomain($path , $routers ,$def = array()){
		foreach ($routers as $k => $v) {
			$reg = str_replace(array('/'), array('\/'), $k);
			$reg = preg_replace_callback(
				'#@([\w]+)(:([^/\(\)]*))?#',
				function($matches) use (&$ids) {
					$ids[$matches[1]] = null;
					if (isset($matches[3])) {
						return '(?P<'.$matches[1].'>'.$matches[3].')';
					}
					return '(?P<'.$matches[1].'>[^\/\?]+)';
				},$reg
				);
			if(preg_match('/^'.$reg.'/',$path,$match)){
                return $v;
			}
		}
		return $def;
	}

	public function load($appPath,$info){
		if(null == $info){
			return $this->file_404();
		}
		$file = $appPath.$this->_cFileName."/".$info['c'].".php";
		if(!file_exists($file)){
			return $this->file_404();
		}else{
			require_once($file);
			$_a = $this->_aPrefix.$info['a'];
			$_c = $this->_cPrefix.$info['c'];
			if(!method_exists($_c,$_a)){
				return $this->file_404();
			}
			$c = new $_c($info['p']);
			return $c->$_a();
		}
	}
	public function file_404(){
		include("404.html");
		exit;
	}

	private function _cls_act($path = "",$params = array(),$res = array("c"=>"Index","a"=>"index","p"=>array())){
		$res['p'] = $params;
		$p = array();
		if(is_string($path) && !empty($path)){
			$p = explode("->",$path);
		}
		switch(count($p)){
			case 1:
				$res['c'] = $p[0];
				break;
			case 2:
				$res['c'] = $p[0];
				$res['a']= $p[1];
				break;
			default:
				break;
		}
		return $res;
	}
}
