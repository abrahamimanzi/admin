<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'name' => 'Title',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'category' => array(
			'name' => 'Category',
			'required' => true
		),
		'description' => array(
			'name' => 'Description',
			'required' => false,
		)
	));
	if($validation->passed()){
	
		$title = sanName(Input::get('title','post'));
		
		$category = data_in(Input::get('category','post'));
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
		
		if($form_error == false){
			try{
				$audioClass->update(array(
					'title' => $title,
					'category' => $category,
					'description' => $description
				),$audio_ID);

				Session::put('success', 'Audio successfully updated!');
				
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