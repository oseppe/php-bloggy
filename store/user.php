<?php //-->

class Store_User {

	public function getDetails($id) {
		$query = 'SELECT * FROM user WHERE user_id = :foo';
		$results = Control::getInstance()->database()->query($query, array('foo' => $id));
		

		if(isset($results[0])) {
			$results = $results[0];
		}
		
		return $results;
	}


	public function findByName($name) {
		//build query
		$query = 'SELECT * FROM user WHERE user_name = :foo';
		
		//execute query
		$results = Control::getInstance()->database()->query($query, array('foo' => $name));
		

		if(isset($results[0])) {
			$results = $results[0];
		}

		return $results;
	}

	//alias function getDetails
	public function findById($id) {
		$this->getDetails($id);
	}

	//find user_name using id
	public function findNameById($id) {
		
		//build query
		$query = 'SELECT user_name FROM user WHERE user_id = :foo';
		
		//execute query
		$result = Control::getInstance()->database()->query($query, array('foo' => $id));
		
		return $result;
	}

	public function create($name) {
		//build query
		$query = 'INSERT INTO user SET user_name = :foo';
		
		//execute query
		Control::getInstance()->database()->query($query, array('foo' => $name));

		// find inserted entry
		$result = $this->findByName($name);

		return $result;
	}

	public function update($id, $name) {
		//build query
		$query = 'UPDATE user SET user_name = :foo WHERE user_id =:bar';
		
		//execute query
		Control::getInstance()->database()->query($query, array('foo' => $name, 'bar' => $id));
		
		// find inserted entry
		$result = $this->findByName($name);

		return $result;
	}

	public function delete($id) {
		//build query
		$query = 'DELETE FROM user WHERE user_id = :foo';

		//execute query
		Control::getInstance()->database()->query($query, array('foo' => $id));	
	}

	public function listAll() {
		//build query
		$query = 'SELECT * FROM user WHERE 1';
		
		//execute query
		$results = Control::getInstance()->database()->query($query, array());

		return $results;
	}
}