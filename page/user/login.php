<?php //-->

class Page_User_Login extends Page {
	protected $_template = '/login.phtml';
	protected $_title = 'Login';

	public function render() {
		//start session
		session_start();

		//get path for config file
		$path = realpath(__DIR__ . '/../../config.php');

		// app id and app secret stored in a separate file for safety
		$config = include($path);

		//get auth
		$auth = eden('facebook')->auth($config['app_id'], 
			$config['app_secret'], 
			$config['loginPath']);
		 
		//if no code and no session
		if(!isset($_GET['code']) && !isset($_SESSION['fb_token'])) {
		    //redirect to login
		    $login = $auth->getLoginUrl(array('publish_actions', 'email'));
		     
		    header('Location: '.$login);
		    exit;
		}
		 
		//Code is returned back from facebook
		if(isset($_GET['code'])) {
		    //save it to session
		    try {
		    	$access = $auth->getAccess($_GET['code']);
		    	$_SESSION['fb_token'] = $access['access_token'];

		    	//instantiate connection to database
		    	$database = eden('mysql', 'localhost', 'blog', 'root', '');
		    	
		    	//is it in database
		    	$graph = eden('facebook')->graph($_SESSION['fb_token'])->getUser(); 
		    	$user = $database->getRow('user', 'user_facebook', $graph['id']);
		    	
		    	//handle if user is not found
		    	if (empty($user)) {
		    		//insert it
		    		$_SESSION['user_name'] = $graph['name'];
		    		$_SESSION['email'] = $graph['email'];
		    		$_SESSION['user_id'] = $database->model()
		    			->setUserName($graph['name'])
		    			->setUserEmail($graph['email'])
		    			->setUserFacebook($graph['id'])
		    			->setUserCreated(date('Y-m-d H:i:s'))
		    			->save('user')
		    			->getUserId();
		    	
		    	}

		    	//user is found
		    	else {
		    		//put user details into session variables
		    		$_SESSION['user_name'] = $user['user_name'];
		    		$_SESSION['email'] = $user['user_email'];
		    		$_SESSION['user_id'] = $user['user_id'];
		    	}

		    } catch (Eden_Error $e) {}
		}

		header('Location:/php-bloggy/training/user');
		exit;
	}

	/*public function render() {
		
		$control = Control::getInstance();
		session_start();		
		//if session variable array user exists
		if (isset($_SESSION['user'])) {
			
			header('location:../user');
			exit;
		}

		// if a name was passed
		if (!empty($_POST)) {
			
			//find name in the database
			$user = $control->store('user')->findByName($_POST['name']);
			
			//if name exists in database
			if (!empty($user)) {

				//assign user_name and user_id to session array variable 
				$_SESSION['user'] = $user;

				//redirect to user page
				header('location:../user');
				exit;
			}

			//if name does not exist
			else {
				
				$this->_body =array(
					'message' => 'User not found'
				);

				return $this->page();

			}
			
		}

		$this->_body =array(
			'message' => ''
		);

		return $this->page();
	}*/
}