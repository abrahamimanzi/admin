<?php
class EventsController  
{
	public static function edit(){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'event-';
		foreach($_POST as $index=>$val){
			$ar = explode($prfx,$index);
			if(count($ar)){
				$_EDIT[end($ar)] = $val;
			}
		}
		$validation = $validate->check($_EDIT, array(
			'name' => array(
				'name' => 'Event name',
				'string' => true,
				'required' => true
			),
			'description' => array(
				'name' => 'Description'
			)
		));
		
		if($validation->passed()){
			$companyTable = new \Company();
			
			$str = new \Str();
			$ID = $str->sanAsName($_EDIT['id']);
			$company = $str->sanAsName($_EDIT['company']);
			$motto = $str->data_in($_EDIT['motto']);
			$email = $str->data_in($_EDIT['email']);
			$telephone = $str->data_in($_EDIT['telephone']);
			$details = $str->data_in($_EDIT['details']);
			$country = $str->sanAsID($_EDIT['country_ID']);
			
			$seconds = \Config::get('time/seconds');
			if($diagnoArray[0] == 'NO_ERRORS'){
				try{
					$companyTable->update(array(
						'company' => $company,
						'motto' => $motto,
						'email' => $email,
						'telephone' => $telephone,
						'details' => $details,
						'country_ID' => $country
					),$ID);
					
				}catch(Exeption $e){
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}else{
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if($diagnoArray[0] == 'ERRORS_FOUND'){
			return (object)[
				'ERRORS'=>true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation()
			];
		}else{
			return (object)[
				'ERRORS'=>false,
				'SUCCESS'=>true,
				'ERRORS_SCRIPT' => ""
			];
		}
	}
    
	public static function add(){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'event-';
		foreach($_POST as $index=>$val){
			$ar = explode($prfx,$index);
			if(count($ar)){
				$_EDIT[end($ar)] = $val;
			}
		}
		$validation = $validate->check($_EDIT, array(
			'name' => array(
				'name' => 'Event name',
				'required' => true
			),
			'description' => array(
				'name' => 'Description'
			)
		));
		
		if($validation->passed()){
			$eventsTable = new \Events();
			
			$str = new \Str();
			$name = $str->sanAsLabel($_EDIT['name']);
			$description = $str->data_in($_EDIT['description']);
            
			$company_ID = $str->data_in($_EDIT['company_ID']);
			$user_ID = Session::get(Config::get('session/session_name'));
            
            $seconds = \Config::get('time/seconds');
            $token_hash = md5($seconds.$company_ID);
			$event_token = $validation->autoUniqueMaker('events','token',$token_hash);
            
			if($diagnoArray[0] == 'NO_ERRORS'){
				try{
					$eventsTable->insert(array(
						'company_ID' => $company_ID,
						'name' => $name,
						'description' => $description,
						'token' => $event_token
					));
					
				}catch(Exeption $e){
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}else{
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if($diagnoArray[0] == 'ERRORS_FOUND'){
			return (object)[
				'ERRORS'=>true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation()
			];
		}else{
			return (object)[
				'ERRORS'=>false,
				'SUCCESS'=>true,
				'ERRORS_SCRIPT' => ""
			];
		}
	}
}