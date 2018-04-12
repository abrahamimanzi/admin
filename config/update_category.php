

<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'name' => 'Name',
			'string' => true,
			'required' => true,
			'min' => 2,
			'max' => 15
		),
		'description' => array(
			'name' => 'Description'
		)
	));
	if($validation->passed()){
		$categoryClass = new Category();
		
		$name = data_in(Input::get('name','post'));
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
		
		try{
			$categoryClass->update(array(
				'name' => $name,
				'description' => $description
			),$category_data->ID);
			
            Session::put('success', 'The Category has been updated successfully!');
			
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