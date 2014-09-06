<?php

class Page_User extends Page{
	protected $_title = 'Users';
	protected $_template = '/user.phtml';
	
	public function render() {
		session_start();
		
		$control = Control::getInstance();
		$list = $control->store('user')->listAll();

		$this->_body = array(
			'rows' => $list,
		);

		return $this->page();
	}
}