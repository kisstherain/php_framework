<?php
require_once "View.php";
class Controller{
    public static $hasCheckUser = false;
	protected $_username;
	protected $render;
	protected $view;
	protected $_params;
	public function initialize(){}
	public function __construct($params = array()){
		$this->_params = $params;
		$this->view = new View(core\Conf::init()->getAppPath().'view');
		$this->view->set(array(
			"_util"=>Util::init(),
			"_config"=>core\Conf::init(),
			"_jspath"=>core\Conf::init()->getConf("JSPATH"),
			"_csspath"=>core\Conf::init()->getConf("CSSPATH"),
			"_imgpath"=>core\Conf::init()->getConf("IMGPATH")
		));
		$this->initialize();
	}
	public function index(){}
}
