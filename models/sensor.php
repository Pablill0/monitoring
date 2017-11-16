<?php
  class Sensor
  {
    private $id;
    private $machine;
    private $model;
    private $description;
    private $max_rank;
    private $mid_rank;
    private $min_rank;
    private $type;

    public function getSensorID(){return $this->id;}
    public function setSensorID($value){$this->id=$value;}
    public function getMachine(){return $this->machine;}
    public function setMachine($value){$this->machine=$value;}
    public function getSensorModel(){return $this->model;}
    public function setSensorModel($value){$this->description=$value;}
    public function getSensorMaxRank(){return $this->max_rank;}
    public function setSensorMaxRank($value){$this->max_rank=$value;}
    public function getSensorMidRank(){return $this->mid_rank;}
    public function setSensorMidRank($value){$this->mid_rank=$value;}
    public function getSensorMinRank(){return $this->min_rank;}
    public function setSensorMinRank($value){$this->min_rank=$value;}
    public function getSensorType(){}
    public function setSensorType($value){}

    function __construct(argument)
    {
      if (func_num_args() == 0)
      {
        $this->id = '';
        $this->model = '';
        $this->description = '';
        $this->max_rank = 0;
        $this->mid_rank=0;
        $this->min_rank=0;
        $this->machine=0;
        $this->type = new sensorType();
      }
      if (func_num_args() == 1)
      {
        $id = func_get_arg(0);
				$connection = MySQLConnection::getConnection();
				$query = 'select s.id, s.machine_id, s.model, s.description, s.max_rank, s.mid_rank, s.min_rank, t.id, t.name from sensor as s inner join sensorType as t on s.type = t.id where s.id=?';
				$command = $connection->prepare($query);
				$command->bind_param('s', $id);
				$command->execute();
				$command->bind_result($id,$machine,$model,$description,$max_rank,$mid_rank,$min_rank,$typeID,$typeName);
				$found = $command->fetch();
				mysqli_stmt_close($command);
				$connection->close();
				if ($found)
				{
					$this->id = $id;
          $this->machine = $machine;
					$this->model = $model;
					$this->description = $description;
          $this->max_rank = $max_rank;
					$this->mid_rank = $mid_rank;
					$this->min_rank = $min_rank;
          $this->type = new sensorType($typeID,$typeName);
				}
				else
				{
					throw new RecordNotFoundException();
				}
      }
      if (func_num_args() == 8)
			{
				$arguments = func_get_args();
				$this->id = $arguments[0];
				$this->model = $arguments[1];
				$this->decription = $arguments[2];
				$this->max_rank = $arguments[3];
        $this->mid_rank = $arguments[4];
        $this->min_rank = $arguments[5];
        $this->machine = $arguments[6];
        $this->type = $arguments[7];
			}
    }
    public function toJson()
    {
      return json_encode(array(
        'id' => $this->id,
        'model' => $this->model,
        'description' => $this->description,
        'max_rank' => $this->max_rank,
        'mid_rank' => $this->mid_rank,
        'min_rank' => $this->min_rank,
        'machine' => json_decode($this->machine->toJson()),
        'type' => json_decode($this->type->toJson())
        ));
    }
  }
?>
