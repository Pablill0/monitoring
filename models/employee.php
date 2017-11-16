<?php
	class Employee {
		private $id;
		private $firstName;
		private $lastName;
		private $phone;
		private $email;
		private $position;
		private $password;
		//getters and setters
		public function getId() { return $this->id; }
		public function setId($value) { $this->id = $value; }
		public function getFirstName() { return $this->firstName; }
		public function setFirstName($value) { $this->firstName = $value; }
		public function getLastName() { return $this->lastName; }
		public function setLastName($value) { $this->lastName = $value; }
		public function getPhone() { return $this->phone; }
		public function setPhone($value) { $this->phone = $value; }
		public function getEmail() { return $this->email; }
		public function setEmail($value) { $this->email = $value; }
		public function getPosition() { return $this->position; }
		public function setPosition($value) { $this->position = $value; }
		public function getPassword() { return $this->password;}
		public function setPassword($value) {$this->position = $value; }
		public function __construct() {
			//empty object
			if (func_num_args() == 0) {
				$this->id = 0;
				$this->firstName = '';
				$this->lastName = '';
				$this->phone ='';
				$this->email = '';
				$this->position = '';
				$this->password = '';
			}
			//object with data from database
			if (func_num_args() == 2) {
				$arguments = func_get_args();
				$id = $arguments[0];
				$password = $arguments[1];
				//get connection
				$connection = MySqlConnection::getConnection();
				//query
				$query = 'select id,firstname,lastname,email,phone,position from employee where id=? and password = ?';
				//command
				$command = $connection->prepare($query);
				//bind parameters
				$command->bind_param('ds', $id,$password);
				//execute
				$command->execute();
				//bind results
				$command->bind_result($this->id, $this->firstName,$this->lastname,$this->email,$this->phone,$this->position);
				//fetch data
				$found = $command->fetch();
				//close command
				mysqli_stmt_close($command);
				//close connection
				$connection->close();
				//throw exception if record not found
				if (!$found) throw new InvalidUserException($id);
			}
			//object with data from arguments
			if (func_num_args() == 7) {
				//get arguments
				$arguments = func_get_args();
				//pass arguments to attributes
				$this->id = $arguments[0];
				$this->firstName = $arguments[1];
				$this->lastName = $arguments[2];
				$this->phone =$arguments[3];
				$this->email = $arguments[4];
				$this->position = $arguments[5];
				$this->password = $arguments[6];
			}
		}
		public function add() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'insert into employee(firstname,lastname,email,phone,position,password) values(??????)';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('ssssss',$this->firstName,$this->lastname,$this->email,$this->phone,$this->position,$this->password);
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
		}
		public function edit() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'update employee set firstname = ?,lastname = ?,email = ?,phone = ?,position = ?,password = ? where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('ssssssss', $this->firstName,$this->lastname,$this->email,$this->phone,$this->position,$this->password,$this->id);
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
		}
		public function delete() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'delete from employee where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('d', $this->id);
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
		}
		public function toJson() {
			return json_encode(array(
				'id' => $this->id,
				'firstname' => $this->firstName,
				'lastname' => $this->lastName,
				'phone' => $this->phone,
				'email' => $this->email,
				'position' => $this->position;
				'password' => $this->password;
			));
		}
		public static function getAll() {
			//list
			$list = array();
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select id,firstname,lastname,email,phone,position,password from employee';
			//command
			$command = $connection->prepare($query);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $name);
			//fetch data
			while ($command->fetch()) {
				array_push($list, new Employee($id, $name));
			}
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return list
			return $list;
		}
	
		//get all in JSON format
		public static function getAllJson() {
			//list
			$list = array();
			//get all
			foreach(self::getAll() as $item) {
				array_push($list, json_decode($item->toJson()));
			}
			//return json encoded array
			return json_encode(array(
				'employee' => $list
			));
		}
	}
?>