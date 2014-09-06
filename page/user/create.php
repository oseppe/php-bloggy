<?php //-->

class Page_User_Create extends Page {
	protected $_template = '/user/create.phtml';
	protected $_title = 'Create a User';
	
	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		$error = false;
		

		// if a name was passed
		if (!empty($_POST)) {
			if (isset($_POST['name']) && strlen($_POST['name']) ) {
				$control->store('user')->create($_POST['name']);
				header('Location: /php-bloggy/training/user');
				exit;
			}
			
			$error = true;
		}

		$this->_body = array(
			'error' => $error
		);

		return $this->page();
	}
}