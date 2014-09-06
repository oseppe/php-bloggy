<?php //-->
abstract class Page {
	
	protected $_body 		= array();
	protected $_title 		= 'Default Title';
	protected $_template 	= NULL;
	
	abstract public function render();
	
	// public function __construct() {
	// 	session_start();
	// }

	public function page() {
		$control 	= Control::getInstance();
		$head = array();

		if(isset($_SESSION['fb_token'])) {
			//get their information
			$graph = eden('facebook')
				    ->graph($_SESSION['fb_token']); 
			try {
		    	$user = $graph->getUser();
				$picture = $graph->getPictureUrl();
				
				$head['user'] = $user;
				$head['picture'] = $picture;
		    } catch (Exception $e) {
		    	unset($_SESSION['fb_token']);
		    }
			
		}
		
		//render the head
		$head = $control->template('/_head.phtml', $head);
		
		//render the body
		$body = $control->template($this->_template, $this->_body);
		
		//render the foot
		$foot = $control->template('/_foot.phtml', array());
		
		return $control->template('/_page.phtml', array(
			'title'	=> $this->_title,
			'head' => $head,
			'body' => $body,
			'foot' => $foot));
	}
}