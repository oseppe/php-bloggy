<?php //-->

class MySQl {
	
	protected static $_connection = NULL;

	protected static $_instance = NULL;

	public static function getInstance($request = NULL) {
		if(!self::$_instance) {
			$class = __CLASS__;

			self::$_instance = new $class($request);
		}

		return self::$_instance;
	}

	public function connect() {
		//if there is a connection do nothing
		if (self::$_connection) {
			return $this;
		}

		//at this point there is no connection
		//lets connect

		self::$_connection = new PDO('mysql:host=localhost;dbname=blog', 'root', '');

		return $this;
	}

	public function getConnection() {
		//connect to the database
		$this->connect();

		return self::$_connection;
	}

	public function query($query, array $binds) {
		$query = $this->getConnection()->prepare($query);

		foreach ($binds as $key => $value) {
			$query->bindValue($key, $value);
		}

		if (!$query->execute()) {
			print_r($query->errorinfo());
			exit;
		}

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}