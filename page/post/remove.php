<?php //-->

class Page_Post_Remove extends Page {
	
	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		
		$id = $variables[0];

		//create quasi query for use in deleteRows method
		$query[] = array('post_id=%s', $id);
		
		//instantiate database connection
		$database = eden('mysql', 'localhost', 'blog', 'root', '');
		
		//delete post from database using id
		$database->deleteRows('post', $query);

		header('Location:/php-bloggy/training/post');
		exit;
	}
}