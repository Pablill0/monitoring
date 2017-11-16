<?php
	//access control
	//allow access from outside the server
	header('Access-Control-Allow-Origin: *');
	//allow methods
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	//allow headers
	header('Access-Control-Allow-Headers: user, token');
	//get headers
	/*$headers = getallheaders();
	//authenticate token
	if(isset($headers['user']) && $headers['token']){
	 	require_once($_SERVER['DOCUMENT_ROOT'].'/project/security/security.php');
	 	if($headers['token'] != Security::generateToken($headers['user'])){
	 		echo json_encode(array(
	 			'status' => 998,
	 			'errorMessage' => 'invalid Security token for user '.$headers['user'],
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
	//Building class
	require_once($_SERVER['DOCUMENT_ROOT'].'/project/models/machinetype.php');
	
	//GET (Read)
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		//parameters
		if (isset($_GET['id'])) {
			try {
				//create object
				$m = new MachineType($_GET['id']);
				//display
				echo json_encode(array(
					'status' => 0,
					'buildingtype' => json_decode($m->toJson())
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
			echo MachineType::getAllJson();
		}
			
	}
	
	//POST (insert)
	/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//check parameters
		if (isset($_POST['id']) &&
			isset($_POST['name']) &&
			isset($_POST['latitude']) &&
			isset($_POST['longitude']) &&
			isset($_POST['typeid']) ) {
			//error
			$error = false;
			//building type
			try {
				$mt = new BuildingType($_POST['typeid']);
			}
			catch (RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Invalid building type'
				));
				$error = true; //found error
			}
			//add building
			if (!$error) {
				//create empty object
				$m = new Building();
				//set values
				$m->setId($_POST['id']);
				$m->setName($_POST['name']);
				$m->setLocation(new Location($_POST['latitude'], $_POST['longitude']));
				$m->setType($mt);
				//add
				if ($m->add())
					echo json_encode(array(
						'status' => 0,
						'message' => 'Building added successfully'
					));
				else
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Could not add building'
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
				isset($jsonData['name']) &&
				isset($jsonData['latitude']) &&
				isset($jsonData['longitude']) &&
				isset($jsonData['typeid']) ) {
				//error
				$error = false;
				//building type
				try {
					$mt = new BuildingType($jsonData['typeid']);
				}
				catch (RecordNotFoundException $ex) {
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Invalid building type'
					));
					$error = true; //found error
				}
				//edit building
				if (!$error) {
					//create empty object
					try {
						$m = new Building($jsonData['id']);
						
						//set values
						$m->setName($jsonData['name']);
						$m->setLocation(new Location($jsonData['latitude'], $jsonData['longitude']));
						$m->setType($mt);
						//add
						if ($m->edit())
							echo json_encode(array(
								'status' => 0,
								'message' => 'Building edited successfully'
							));
						else
							echo json_encode(array(
								'status' => 5,
								'errorMessage' => 'Could not edit building'
							));
					}
					catch (RecordNotFoundException $ex) {
						echo json_encode(array(
							'status' => 4,
							'errorMessage' => 'Invalid building id'
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
				$m = new Building($putData['id']);
				//delete
				if ($m->delete())
					echo json_encode(array(
						'status' => 0,
						'errorMessage' => 'Building deleted successfully'
					));
				else
					echo json_encode(array(
						'status' => 3,
						'errorMessage' => 'Could not delete building'
					));
			}
			catch(RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => 'Invalid building id'
				));
			}
		}
		else {
			echo json_encode(array(
				'status' => 1,
				'errorMessage' => 'Missing id parameter'
			));
		}
	}*/
?>









