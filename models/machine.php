<?php
	//use files
	require_once('mysqlconnection.php');
	require_once('machineType.php');
	
	class Machine {
		//attributes
		private $id;
		private $model;
		private $type;
		private $description;
		private $area;
		private $status;
		
		//getters and setters
		public function getId() { return $this->id; }
		public function setId($value) { $this->id = $value; }
		public function getModel() { return $this->model; }
		public function setModel($value) { $this->model = $value; }
		public function getDescription() { return $this->description; }
		public function setDescription($value) { $this->description = $value; }
		public function getStatus() { return $this->status;}
		public function setStatus($value) { $this->status = $value; }
		public function getType() { return $this->type; }
		public function setType($value) { $this->type = $value; }
		
		//constructor
		public function __construct() {
			//empty object
			if (func_num_args() == 0) {
				$this->id = 0;
				$this->model = '';
				$this->type = 0;
				$this->description = '';
				$this->area = 0;
				$this->status = '';

			}
			//object with data from database
			if (func_num_args() == 1) {
				//get id
				$id = func_get_arg(0);
				//get connection
				$connection = MySqlConnection::getConnection();
				//query
				$query = 'select m.id, m.model, m.description,m.status, mt.id, mt.name, a.id, a.name
						  from machine as m join machineType as mt on m.type = bt.id join area as a on m.area = a.id
						  where m.id = ?';
				//command
				$command = $connection->prepare($query);
				//bind parameters
				$command->bind_param('s', $id);
				//execute
				$command->execute();
				//bind results
				$command->bind_result($id, $model, $description,$status,$typeid,$typename,$areaid,$areaname);
				//fetch data
				$found = $command->fetch();
				//close command
				mysqli_stmt_close($command);
				//close connection
				$connection->close();
				//pass values to the attributes
				if ($found) {
					$this->id = $id;
					$this->model = $model;
					$this->status = $status;
					$this->description = $description;
					$this->type = new MachineType($typeid, $typename);
					$this->area = new Area($areaid,$areaname);
				}
				else {		
					//throw exception if record not found
					throw new RecordNotFoundException();
				}
			}
			//object with data from arguments
			if (func_num_args() == 6) {
				//get arguments
				$arguments = func_get_args();
				//pass arguments to attributes
				$this->id = $arguments[0];
				$this->model = $arguments[1];
				$this->status = $arguments[2];
				$this->description = $arguments[3];
				$this->type = $arguments[4];
				$this->area = $arguments[5];
			}
		}
		
		//instance methods
		
		//add
		public function add() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'insert into machines (model, type, description, area, status) values(?, ?, ?, ?, ?, ?)';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('sdsds', $this->model, $this->type->getId(), $this->description, $this->area->getId());
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
		}
		//edit
		public function edit() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'update machine 
					  set model = ?, type = ?, description = ?, area = ?, status = ? 
					  where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('sdsdsd', 
				$this->model, 
				$this->type->getId(), 
				$this->description, 
				$this->area->getId(),
				$this->status);
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
			
		}
		
		//delete
		public function delete() {
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'delete from machine where id = ?';
			//command
			$command = $connection->prepare($query);
			//bind parameters
			$command->bind_param('s', $this->id);
			//execute
			$result = $command->execute();
			//close command
			mysqli_stmt_close($command);
			//close connection
			$connection->close();
			//return result
			return $result;
		}
	
		//represents the object in JSON format
		public function toJson() {
			return json_encode(array(
				'id' => $this->id,
				'model' => $this->model,
				'type' => json_decode($this->type->toJson()),
				'description' => $this->description,
				'area' => json_decode($this->area->toJson()),
				'status' => $this->status
			));
		}
		
		//class methods
		//get all
		public static function getAll() {
			//list
			$list = array();
			//get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select m.id, m.model, m.description,m.status, mt.id, mt.name, a.id, a.name
						  from machine as m join machineType as mt on m.type = bt.id join area as a on m.area = a.id order by m.id';
			//command
			$command = $connection->prepare($query);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $model, $description, $status, $typeid,$typename,$areaid, $areaname);
			//fetch data
			while ($command->fetch()) {
				$type = new MachineType($typeid,$typename);
				$area = new Area($areaid,$areaname);
				array_push($list, new Building($id, $model, $description, $status,$type,$area));
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
				'machines' => $list
			));
		}
	}
?>








