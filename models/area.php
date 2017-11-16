<?php
  class Area
  {
    private $id;
    private $name;
    private $employee;

    public function getAreaID(){return $this->id;}
    public function setAreaID($value){$this->id = $value;}
    public function getAreaName(){return $this->name;}
    public function setAreaName($value){$this->name = $value;}
    public function getEmployeeData(){return $this->employee}
    public function setEmployeeData($value){ $this->employee=$value;}

    function __construct()
    {
      if (func_num_args() == 0)
			{
				$this->id = '';
				$this->name = '';
				$this->employee = new Employee();
			}
      if (func_num_args() == 1)
      {
				$id = func_get_arg(0);
				$connection = MySQLConnection::getConnection();
				$query = 'select a.id, a.name, e.id, e.firstname, e.lastname, e.email, e.phone, e.position, e.password from area as a inner join employee as e on a.supervisor = e.id where a.id =?';
				$command = $connection->prepare($query);
				$command->bind_param('s', $id);
				$command->execute();
				$command->bind_result($id,$name,$idEmployee,$firstname,$lastname,$email,$phone,$position,$password);
				$found = $command->fetch();
				mysqli_stmt_close($command);
				$connection->close();
				if ($found)
				{
					$this->id = $id;
					$this->name = $name;
					$this->employee = new Employee($idEmployee,$firstname,$lastname,$email,$phone,$position,$password);
				}
				else
				{
					throw new RecordNotFoundException();
				}
      }
      if (func_num_args() == 3)
			{
				$arguments = func_get_args();
				$this->id = $arguments[0];
				$this->name = $arguments[1];
				$this->employee = $arguments[2];
			}
    }

    public function toJson()
    {
      return json_encode(array(
        'id' => $this->id,
        'name' => $this->name,
        'supervisor' => json_decode($this->employee->toJson())
        ));
    }
  }
?>
