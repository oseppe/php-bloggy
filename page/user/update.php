<?php //-->

class Page_User_Update extends Page {
	protected $_template = '/user/update.phtml';
	protected $_title = 'Update User';

	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		$error = false;
		$id = $variables[0];

		

		// if a name was passed
		if (!empty($_POST)) {
			if (isset($_POST['name']) && strlen($_POST['name']) ) {
				
				$control->store('user')->update($id, $_POST['name']);
				header('Location: /training/user');
				exit;
			}
			
			$error = true;
		}

		$name = $control->store('user')->findNameById($id);
	
		$name = $name[0]['user_name'];
		$user = $control->store('user')->getDetails($id);

		$this->_body =array(
			'error' => $error,
			'name' => $name
		);

		return $this->page();
	}
}