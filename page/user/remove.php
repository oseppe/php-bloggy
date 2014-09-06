<?php //-->

class Page_User_Remove {

	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		
		$id = $variables[0];

		//create quasi query for use in deleteRows method
		$query[] = array('user_id=%s', $id);
		
		//instantiate database connection
		$database = eden('mysql', 'localhost', 'blog', 'root', '');
		
		//delete post from database using id
		$database->deleteRows('user', $query);

		header('Location:/php-bloggy/training/user');
		exit;
	}
}