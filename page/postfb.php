<?php //-->

class Page_Postfb extends Page {
	protected $_template = '/postfb.phtml';
	protected $_title = 'POST!';
	
	public function render() {
		
		$control = Control::getInstance();
		$variables = $control->getVariables();
		$error = false;
		session_start();
		

		// if a name was passed
		if (!empty($_POST)) {
			if (isset($_POST['name']) && strlen($_POST['name']) && isset($_SESSION['fb_token']) ) {
				$graph = eden('facebook')->graph($_SESSION['fb_token']);
				
				try {
			    	$post = $graph->post($_POST['name']);
					$post->create(); 
			    } catch (Exception $e) {
			    	unset($_SESSION['fb_token']);
			    }

				header('Location:/php-bloggy/training/user');
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