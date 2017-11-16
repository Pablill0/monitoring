<?php
	//access control
	//allow access from outside the server
	header('Access-Control-Allow-Origin: *');
	//allow methods
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	//allow headers
	header('Access-Control-Allow-Headers: user, token');
	//get headers
	$headers = getallheaders();
	//authenticate token
	/*require_once($_SERVER['DOCUMENT_ROOT'].'/project/security/security.php');
	if(isset($headers['user']) && $headers['token']){
	 	require_once($_SERVER['DOCUMENT_ROOT'].'/project/security/security.php');
	 	if($headers['token'] != Security::generateToken($headers['user'])){
	 		echo json_encode(array(
	 			'status' => 998,
	 			'errorMessage' => 'invalid Security token for user '.$headers['user'];
	 			)); die();
	 	}
	 }
	 else{
	 	echo json_encode(array(
	 			'status' => 998,
	 			'error' => 'Missing security headers'
	 		));
	 	die();
	 }*/
	//Machine class
	require_once($_SERVER['DOCUMENT_ROOT'].'/project/models/machine.php');
	
	//GET (Read)
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		//parameters
		if (isset($_GET['id'])) {
			try {
				//create object
				$m = new Machine($_GET['id']);
				//display
				echo json_encode(array(
					'status' => 0,
					'machine' => json_decode($m->toJson())
				));
			}
			catch (RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 1,
					'errorMessage' => $ex->get_message()
				));
			}
		}
		else {
			echo Machine::getAllJson();
		}
			
	}
	
	//POST (insert)
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//check parameters
		if (isset($_POST['model']) &&
			isset($_POST['type']) &&
			isset($_POST['description']) &&
			isset($_POST['area']) &&
			isset($_POST['status']) ) {
			//error
			$error = false;
			//machine type
			try {
				$mt = new MachineType($_POST['type']);
			}
			catch (RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Invalid machine type'
				));
				$error = true; //found error
			}
			try {
				$a = new Area($_POST['area']);
			}
			catch (RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Invalid machine type'
				));
				$error = true; //found error
			}
			//add machine
			if (!$error) {
				//create empty object
				$m = new Machine();
				//set values
				$m->setModel($_POST['model']);
				$m->setType(new MachineType($_POST['idType'], $_POST['nameType']));
				$m->setType($mt);
				//add
				if ($m->add())
					echo json_encode(array(
						'status' => 0,
						'message' => 'Machine added successfully'
					));
				else
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Could not add machine'
					));
			}
		}
		else 
			echo json_encode(array(
				'status' => 1,
				'errorMessage' => 'Missing parameters'
			));
	}
	
	//PUT (update)
	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		//read data
		parse_str(file_get_contents('php://input'), $putData);
		if (isset($putData['data'])) {
			//decode json
			$jsonData = json_decode($putData['data'], true);
			//check parameters
			if (isset($jsonData['id']) &&
				isset($jsonData['model']) &&
				isset($jsonData['type']) &&
				isset($jsonData['description']) &&
				isset($jsonData['area']) &&
				isset($jsonData['status']) ) {
				//error
				$error = false;
				//machine type
				try {
					$mt = new MachineType($jsonData['type']);
				}
				catch (RecordNotFoundException $ex) {
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Invalid machine type'
					));
					$error = true; //found error
				}
				try {
					$a = new Area($jsonData['area']);
				}
				catch (RecordNotFoundException $ex) {
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Invalid area'
					));
					$error = true; //found error
				}
				//edit machine
				if (!$error) {
					//create empty object
					try {
						$m = new Machine($jsonData['id']);
						
						//set values
						$m->setModel($jsonData['model']);
						$m->setDescription($jsonData['description']);
						$m->setArea($a);
						$m->setType($mt);
						//add
						if ($b->edit())
							echo json_encode(array(
								'status' => 0,
								'message' => 'Machine edited successfully'
							));
						else
							echo json_encode(array(
								'status' => 5,
								'errorMessage' => 'Could not edit machine'
							));
					}
					catch (RecordNotFoundException $ex) {
						echo json_encode(array(
							'status' => 4,
							'errorMessage' => 'Invalid machine id'
						));
					}
				}	
			}
			else
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Missing parameters'
				));
		}
		else
			echo json_encode(array(
				'status' => 1,
				'errorMessage' => 'Missing data parameter'
			));
	}
	
	//DELETE (delete)
	if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
		//read id
		parse_str(file_get_contents('php://input'), $putData);
		if (isset($putData['id'])) {
			try {
				//create object
				$m = new Machine($putData['id']);
				//delete
				if ($m->delete())
					echo json_encode(array(
						'status' => 0,
						'errorMessage' => 'Machine deleted successfully'
					));
				else
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Could not delete machine'
					));
			}
			catch(RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Invalid machine id'
				));
			}
		}
		else {
			echo json_encode(array(
				'status' => 1,
				'errorMessage' => 'Missing id parameter'
			));
		}
	}
?>









