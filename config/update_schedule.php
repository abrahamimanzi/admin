<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'speaker_title' => array(
			'name' => 'Speaker title',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'serie_title' => array(
			'name' => 'Serie title',
			'required' => true
		),
		'description' => array(
			'name' => 'Description'
		)
	));
	if($validation->passed()){
	$scheduleClass = new Schedule();
		
		$speaker_title = sanName(Input::get('speaker_title','post'));
		$serie_title = sanLabel(Input::get('serie_title','post'));
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
		
		
		if($form_error == false){
			try{
				$scheduleClass->update(array(
					'speaker_title' => $speaker_title,
					'serie_title' => $serie_title,
					'description' => $description
				),$schedule_data->ID);
				
				Session::put('success', 'The agent has been updated successfully!');
				
			}catch(Exeption $e){
				$form_error = true;
				Session::put('errors', $e->getMessage());
			}
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
?>