

<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'name' => 'Name',
			'string' => true,
			'required' => true,
			'min' => 2,
			'max' => 25
		),
		'media' => array(
			'name' => 'Media',
			'required' => true
		),
		'description' => array(
			'name' => 'Description'
		)
	));
	if($validation->passed()){
		$categoryClass = new CategoryMedia();
		
		$name = data_in(Input::get('name','post'));
		$media = data_in(Input::get('media','post'));
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
		
		try{
			$categoryClass->update(array(
				'name' => $name,
				'media' => $media,
				'description' => $description
			),$category_data->ID);
			
            Session::put('success', 'The Media Category has been updated successfully!');
			
		}catch(Exeption $e){
			$form_error = true;
			Session::put('errors', $e->getMessage());
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
?>