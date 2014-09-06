<?php
class Page_User_Logout extends Page {
	protected $_title = 'Logout';

	public function render() {
		
		session_start();
		$graph = eden('facebook')->graph($_SESSION['fb_token']);
		
		// delete $_SESSION
		session_unset();
		
		//compose redirect url
		$path = 'http://localhost/php-bloggy/training/user';
		
		//get url for logging out of facebook then redirecting to front page
		$userPage = $graph->getLogoutUrl($path);

		header('Location: '.$userPage);
		exit;
	}
}
	
