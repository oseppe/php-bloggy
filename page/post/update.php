<?php //-->

class Page_Post_Update extends Page {
	protected $_template = '/post/update.phtml';
	protected $_title = 'Update Post';

	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		$error = false;
		$id = $variables[0];

		// check
		$changeTitle = 'no';
		$changeDetail = 'no';

		//instantiate database connection
		$database = eden('mysql', 'localhost', 'blog', 'root', '');

		$data = $database->getRow('post', 'post_id', $id);
		$filter[] = array('post_id=%s', $id);

		// if data was passed
		if (!empty($_POST)) {
			if (isset($_POST['title'], $_POST['detail']) ) {

				// if title was changed
				if (!empty($_POST['title'])) {
					$new_title = array('post_title' => $_POST['title']);
					$database->updateRows('post', $new_title, $filter);
				}

				//if detail was changed
				if (!empty($_POST['detail'])) {
					$new_detail = array('post_detail' => $_POST['detail']);
					$database->updateRows('post', $new_detail, $filter);	
				}
				
				header('Location:/php-bloggy/training/post');
				exit;
			}
			
			$error = true;
		}

		$this->_body =array(
			'error' => $error,
			'data' => $data
		);

		return $this->page();
	}
}