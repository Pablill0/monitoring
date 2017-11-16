<?php
require_once('mysqlconnection.php');
require_once('exceptions/recordnotfoundexception.php');
  class SensorType
  {
    private $id;
    private $name;

    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function getName(){return $this->name;}
    public function setName($value){$this->name = $value;}

    function __construct()
    {
      if (func_num_args() == 0)
			{
				$this->id = 0;
				$this->name = '';
			}
      if (func_num_args() == 1)
      {
				$id = func_get_arg(0);
				$connection = MySQLConnection::getConnection();
				$query = 'select id, name from sensorType where id =?';
				$command = $connection->prepare($query);
				$command->bind_param('d', $id);
				$command->execute();
				$command->bind_result($id,$name);
				$found = $command->fetch();
				mysqli_stmt_close($command);
				$connection->close();
				if ($found)
				{
					$this->id = $id;
					$this->name = $name;
				}
				else
				{
					throw new RecordNotFoundException();
				}
      }
    }

    public function add()
		{
			$connection = MySqlConnection::getConnection();
			$query = 'insert into sensorType (name) values(?)';
			$command = $connection->prepare($query);
			$command->bind_param('s',$this->name);
			$result = $command->execute();
			mysqli_stmt_close($command);
			$connection->close();
			return $result;
		}

    public static function getAll()
    {
      $list = array();
      $connection = MySqlConnection::getConnection();
      $query = 'select id, name from sensorType';
      $command = $connection->prepare($query);
      $command->execute();
      $command->bind_result($id, $name);
      while ($command->fetch())
      {
        array_push($list,$id,$name);
      }
      mysqli_stmt_close($command);
      $connection->close();
      return $list;
    }

    public function toJson()
    {
      return json_encode(array('id' => $this->id, 'name' => $this->name));
    }
  }
?>
