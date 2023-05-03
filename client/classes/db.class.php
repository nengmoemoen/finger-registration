<?php 

class db {

	protected static $db;

	private function __construct() {

		try {
			// assign PDO object to db variable 
			self::$db = new PDO( 'mysql:host=localhost;dbname=member_finger', 'root', 'root');
			self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (PDOException $e) {
			echo "Connection Error: " . $e->getMessage();
		}
	}

	public static function getInstance() {
		if (!self::$db) {
			new db();
		}
		
		//return connection.
		return self::$db;
	}
}

?>