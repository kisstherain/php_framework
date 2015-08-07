<?php
class Controller_Test extends Controller{
    public function initialize(){
        //$this->checkUser();
    }
	public function action_index(){
		$this->view->set(array(
			"test"=>"test",
		));

        $this->view->layout = "layout/default";
		$this->view->content("test/index");
		$this->view->show();
	}
}
