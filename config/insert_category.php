

<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'name' => 'Name',
			'string' => true,
			'required' => true,
			'unique' => 'category',
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
		
        if($form_error == false){
            try{
                $categoryClass->insert(array(
                    'name' => $name,
					'description' => $description
                ));
				Session::put('success', 'The category has been recorded successfully!');

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