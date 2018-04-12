<?php
class ParticipantCategoryController  
{
	public static function add(){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'category-';
		foreach($_POST as $index=>$val){
			$ar = explode($prfx,$index);
			if(count($ar)){
				$_EDIT[end($ar)] = $val;
			}
		}
		$validation = $validate->check($_EDIT, array(
			'name' => array(
				'name' => 'Category name',
				'string' => true,
				'unique' => 'events_participant_category',
				'required' => true
			),
			'prefix' => array(
				'name' => 'Category name',
				'required' => true
			),
			'url' => array(
				'name' => 'Url string',
				'required' => true
			)
		));
		
		if($validation->passed()){
			$participantCategTable = new \ParticipantCategory();
			
			$str = new \Str();
			$name = $str->sanAsName($_EDIT['name']);
			$prefix = strtoupper($_EDIT['prefix']);
			$url = $_EDIT['url'];
			
			$seconds = \Config::get('time/seconds');
			if($diagnoArray[0] == 'NO_ERRORS'){
				try{
					$participantCategTable->insert(array(
						'name' => $name,
						'prefix' => $prefix,
						'url' => $url
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
?>