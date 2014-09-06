<?php //-->

class Page_Post_Create extends Page {
	protected $_template = '/post/create.phtml';
	protected $_title = 'Create a Post';
	
	public function render() {
		session_start();
		$control = Control::getInstance();
		$variables = $control->getVariables();
		$error = false;
		
		// if(!isset($_SESSION['fb_token'])){
		// }

		// if a name was passed
		if (!empty($_POST)) {
			if (isset($_POST['title'], $_POST['detail'] ) 
				&& !empty($_POST['title'])
				&& !empty($_POST['detail']) ) {

				// save post to database
				eden('mysql', 'localhost', 'blog', 'root', '')
					->model()
					->setPostTitle($_POST['title'])
					->setPostDetail($_POST['detail'])
					->setPostUser($_SESSION['user_id'])
					->save('post');
				
				header('Location:/php-bloggy/training/post');
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