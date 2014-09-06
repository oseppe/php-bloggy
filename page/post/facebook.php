<?php //-->

class Page_Post_Facebook extends Page {
	

	public function render() {
		
		session_start();

		$control = Control::getInstance();
		$variables = $control->getVariables();
		
		$id = $variables[0];

		//instantiate database connection
		$database = eden('mysql', 'localhost', 'blog', 'root', '');

		//get post from database
		$data = $database->getRow('post', 'post_id', $id);

		//create message
		//if no post
			//redirect to post
		$graph = eden('facebook')->graph($_SESSION['fb_token']);

		$post = $graph->post($data['post_detail']);
		$post->create();
		
		header('Location:/php-bloggy/training/post');
		exit;
	}
}

//get variables

//get post from database

//if no post
	//redirect to post
	
//at this point we have a post