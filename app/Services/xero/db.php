<?php
class db {
	private $conn;
	private $host;
	private $user;
	private $password;
	private $baseName;
	private $port;
	private $Debug;

	function __construct($params=array()) {
		$this->conn = false;
		$this->host = 'localhost'; //hostname
		$this->user = 'wldhzlxq_mobbins'; //username
		$this->password = 'mobbins1789'; //password
		$this->baseName = 'wldhzlxq_ilease'; //name of your database
		$this->port = '';
		$this->debug = true;
		$this->connect();
	}

	function __destruct() {
		$this->disconnect();
	}

	function connect() {
		if (!$this->conn) {
			$this->conn = new mysqli($this->host, $this->user, $this->password,$this->baseName);
			//mysqli_select_db($this->baseName, $this->conn);
			//mysqli_set_charset('utf8',$this->conn);

			if (!$this->conn) {
				$this->status_fatal = true;
				echo 'Connection BDD failed';
				die();
			}
			else {
				$this->status_fatal = false;
			}
		}

		return $this->conn;
	}

	function disconnect() {
		if ($this->conn) {
			//@pg_close($this->conn);
		}
	}

	function getOne($query) { // getOne function: when you need to select only 1 line in the database
		$cnx = $this->conn;

		if (!$cnx || $this->status_fatal) {
			echo 'GetOne -> Connection BDD failed';
			die();
		}

		$cur = mysqli_query($cnx,$query );

		if ($cur == FALSE) {

			$errorMessage = mysqli_error($cur);
			$this->handleError($query, $errorMessage);
		}
		else {

			$this->Error=FALSE;
			$this->BadQuery="";
			$tmp = mysqli_fetch_array($cur);

			$return = $tmp;
		}

		@mysqli_free_result($cur);
		return $return;
	}

	function getAll($query) { // getAll function: when you need to select more than 1 line in the database
		$cnx = $this->conn;
		if (!$cnx || $this->status_fatal) {
			echo 'GetAll -> Connection BDD failed';
			die();
		}


		$cur = mysqli_query($cnx,$query);
		$return = array();

		while($data = mysqli_fetch_assoc($cur)) {
			array_push($return, $data);
		}

		return $return;
	}

	function execute($query,$use_slave=false) { // execute function: to use INSERT or UPDATE
		 //echo $query; die;
		$cnx = $this->conn;
		if (!$cnx||$this->status_fatal) {
			return null;
		}

		$cur = @mysqli_query( $cnx,$query);
		print_r($cur);
		if ($cur == FALSE) {
			$ErrorMessage = @mysqli_error($cnx);
			$this->handleError($query, $ErrorMessage);
		}
		else {
			$this->Error=FALSE;
			$this->BadQuery="";
			$this->NumRows = mysqli_affected_rows();
			return;
		}
		@mysqli_free_result($cur);
	}

	function handleError($query, $str_erreur) {
		$this->Error = TRUE;
		$this->BadQuery = $query;


			echo "Query : ".$query."<br>";
			echo "Error : ".$str_erreur."<br>";
		die;
	}
}
?>
