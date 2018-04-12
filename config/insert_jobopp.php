<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'position' => array(
			'name' => 'Position',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'details' => array(
			'name' => 'Details',
			'required' => true
		),
		'deadline' => array(
			'name' => 'Deadline',
			'required' => true
		)
	));
	if($validation->passed()){
		$jobOppClass = new JobOpp();
		
		$position = data_in(Input::get('position','post'));
		$details = data_in(Input::get('details','post'));
		$deadline = data_in(Input::get('deadline','post'));
		
		$link = '';
		$link_label = '';
		if(Input::checkInput('link','post',1)){
			$link = srg_txt(Input::get('link','post'));
			$link_label = srg_txt(Input::get('link_label','post'));
		}
		
		$temp = Config::get('time/temp');
        
        if($form_error == false){
            try{
                $jobOppClass->insert(array(
                    'position' => $position,
                    'details' => $details,
                    'link_label' => $link_label,
                    'link' => $link,
                    'deadline'	 => $deadline,
                    'state' => 'Published',
                    'created_date'	=> Config::get('time/temp')
                ));
                Session::put('success', 'The Job Opps has been recorded successfully!');
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