<?php //-->
require('../page.php');
require('../eden.php');

//autoload classes
eden()->setLoader(NULL);

class Control {

	protected $_request = NULL;
	protected $_variables = array();
	protected $_response = 'Page_Index';

	protected static $_instance = NULL;

	public static function getInstance($request = NULL) {
		if(!self::$_instance) {
			$class = __CLASS__;

			self::$_instance = new $class($request);
		}

		return self::$_instance;
	}

	public function __construct($request) {
		$this->_request = $request;

		$this->route();
	}

	public function route() {
		$this->_variables = array();

		//1. Remove /training e.g. /training/user/123/comments -> /user/123/comments
		$request = substr($this->_request, 20);

		//1.a remove ? from request
		if (strpos($request, '?') !== false) {
			$request = substr($request, 0, strpos($request, '?'));			
		}
		

		//2. prefer the route logic over the default one

		//load up the route file
		$match = '';
		$routes = require('../route.php');

		foreach ($routes as $regex => $class) {
			//compare the regex with the request
			//format the regex e.g. /user/%/comments
			//2a. /user/%/comments -> \/user\/%\/comments escape forward slashes
			$regex = str_replace('/', '\/', $regex);

			//2b. \/user\/%\/comments -> \/user\/.+\/comments 
			$regex = str_replace('%', '(.+)', $regex);

			//2c./^\/user\/.+\/comments/ 
			$regex = '/^' . $regex . '\/*(.+)/';

			//2c compare now
			if(preg_match($regex, $request, $matches) && strlen($regex) > strlen($match)) {
				print_r($matches);
				//this matches
				$this->_response = $class;
				$match = $regex;
				array_shift($matches);
				$this->_variables = $matches;
			}			
		}

		//did we find a match?
		if(strlen($match) > 0) {

			//we need to require the path

			//we have response as e.g. Page_User_Comments
			//chnage to /page/user/comments.php
			$path = str_replace('_', '/', strtolower($this->_response));
			$path = realpath(__DIR__ . '/../' . $path . '.php');

			require_once($path);
		} 

		if($this->_response == 'Page_Index' ) {
			//3. Explode the request by /
			$request = explode('/', $request);

			//3. Tranverse backwards
			do {
				//join request
				//form the entire path
				$path = realpath(__DIR__ . '/../page' . implode('/', $request) . '.php');

				if (file_exists($path)) {
					//determine

					// user/comments -> user comments
					$class = str_replace('/', ' ', implode('/', $request));


					$class = ucwords($class);

					//user comments -> _User_Comments

					$class = str_replace(' ', '_', $class);

					//this is the response class -- _User_Comments -> Page_User_Comments
					$this->_response = 'Page'. $class;

					//path is our response
					require_once($path);

					//break out of loop
					break;
				}
			} while($this->_variables[] = array_pop($request)); 
		}

		
		
		if($this->_response == 'Page_Index') {
			require_once(real_path(__DIR__ . '/../page/index.php'));
		}

		return $this;
	}

	public function output() {
		$class = $this->_response;

		$response = new $class();

		echo $response->render();
	}

	public function action() {
		//get parameters from url
		$parameters = $_GET;

		//get action requested
		$action = $parameters['action'];

		if($action === 'create') {

			//if a name was passed
			if (isset($parameters['name'])) {
				//create a user using the name passed
				$this->store('user')->create($parameters['name']);
			}

			//if no name was passed
			else {
				echo 'Must pass a name';
			}
		}

		else if($action === 'delete') {

			//if an id was passed
			if (isset($parameters['id'])) {
				//delete a user using the id passed
				$this->store('user')->delete($parameters['id']);
			}

			//if no id was passed
			else {
				echo 'Must pass an id';
			}
		}

		else if ($action === 'get') {

			//if a name was passed
			if (isset($parameters['name'])) {
				$this->store('user')->findByName($parameters['name']);
			}

			//if an id was id
			else if (isset($parameters['id'])) {
				$this->store('user')->findById($parameters['id']);
			}

			//if no id or name was passed
			else {
				echo 'Must pass an id or name';
			}
		}

		else if ($action === 'get_all') {

			//retrieve all user entries in database
			$this->store('user')->listAll();
		}

		else if($action === 'update') {

			//if an id and a name was passed
			if (isset($parameters['id']) && isset($parameters['name'])) {
				//update existing user
				$this->store('user')->update($parameters['id'], $parameters['name']);
			}

			//if no id AND name was passed
			else {
				echo 'Must pass an id AND name';
			}
		}

		else {
			echo "I dont know that action";
		}
		
	}

	// public function create($name) {
	// 	$query = 'INSERT INTO user SET user_name = :foo';
	// 	Control::getInstance()->database()->query($query, array('foo' => $name));
	// }

	public function update($id, $name) {
	
		$query = 'UPDATE user SET user_name = :foo WHERE user_id =:bar';
		Control::getInstance()->database()->query($query, array('foo' => $name, 'bar' => $id));
	}

	public function delete($id) {
		$query = 'DELETE FROM user WHERE user_id = :foo';
		Control::getInstance()->database()->query($query, array('foo' => $id));	
	}

	public function list_all() {
		$query = 'SELECT * FROM user WHERE 1';
		print_r(Control::getInstance()->database()->query($query, array()));
	}

	public function getVariables() {
		return $this->_variables;
	}

	public function template($___file, array $___data) {
		//pull the data into variable key values
		$___file = realpath(__DIR__ . '/../page' . $___file);
		extract($___data);

		//start output buffer
		ob_start();

		//output and buffer will grab the output
		//without outputting it to the screen
		include($___file);

		//ninja the output buffer
		$___contents = ob_get_contents();

		//close the output buffer
		//so that next time output buffer it wik show on the screen
		ob_end_clean();

		return $___contents;
	}

	public function store($model) {
		//figure out the path aand class name

		$path = realpath(__DIR__ . '/../store/' . $model . '.php');
		$class = 'Store_' . ucwords($model);

		
		require_once($path);

		return new $class();
	}

	public function database() {
		//figure out the path aand class name

		$path = realpath(__DIR__ . '/../mysql.php');
		
		require_once($path);

		return MySQL::getInstance();
	}


}