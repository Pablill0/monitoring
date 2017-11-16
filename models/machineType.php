<?php
	class MachineType {
		private $id;
		private $name;
		//getters and setters
		public function getId() { return $this->id; }
		public function setId($value) { $this->id = $value; }
		public function getName() { return $this->name; }
		public function setName($value) { $this->name = $value; }

		public function __construct() {
			//empty object
			if (func_num_args() == 0) {
				$this->id = 0;
				$this->name = '';
			}
			//object with data from database
			if (func_num_args() == 1) {
				//get id
				$id = func_get_arg(0);
				//get connection
				$connection = MySqlConnection::getConnection();
				//query
				$query = 'select id,name from machineType where id=?';
				//command
				$command = $connection->prepare($query);
				//bind parameters
				$command->bind_param('s', $id);
				//execute
				$command->execute();
				//bind results
				$command->bind_result($this->id, $this->name);
				//fetch data
				$found = $command->fetch();
				//close command
				mysqli_stmt_close($command);
				//close connection
				$connection->close();
				//throw exception if record not found
				if (!$found) throw new RecordNotFoundException();
			}
			//object with data from arguments
			if (func_num_args() == 2) {
				//get arguments
				$arguments = func_get_args();
				//pass arguments to attributes
				$this->id = $arguments[0];
				$this->name = $arguments[1];
			}
		}
		public function add() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'insert into machineType (name) values(?)';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('s', $this->name);
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
			$query = 'update machineType set name = ? where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('d', $this->name);
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
			$query = 'delete from machineType where id = ?';
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
				'name' => $this->name,
			));
		}
		public static function getAll() {
			//list
			$list = array();
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select id,name from machineType';
			//command
			$command = $connection->prepare($query);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $name);
			//fetch data
			while ($command->fetch()) {
				array_push($list, new MachineType($id, $name));
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
				'machineType' => $list
			));
		}
	}
?>