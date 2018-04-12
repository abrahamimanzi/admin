 <?php
	$form_error = false;
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'post_ID' => array(
			'name' => 'Post',
			'required' => true
		),
		'fullname' => array(
		'name' => 'Full Name',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'email' => array(
			'name' => 'Email',
			'required' => true,
			'email' => true
		),
		'comment' => array(
			'name' => 'Comment',
			'required' => true
		)
	));
	if($validation->passed()){
		$commentClass = new Comments();
		
		
		$post_ID = sanAsID(Input::get('post_ID','post'));
		$fullname = sanName(Input::get('fullname','post'));
		$email = data_in(Input::get('email','post'));
		$comment = data_in(Input::get('comment','post'));
		$ip_address = $session_ipa;
		
		$temp = Config::get('time/temp');
		
        
        if($form_error == false){
            try{
                $commentClass->insert(array(
                    'post_ID' => $post_ID,
                    'fullname' => $fullname,
                    'email' => $email,
                    'comment' => $comment,
                    'ip_address' => $ip_address,
                    'state' => 'Shelved',
                    'created_date'	=> Config::get('time/temp')
                ));

                Session::put('success', 'The Comment has been submited successfully!');

            }catch(Exeption $e){
				$form_error = true;
				$error_msg = $e->getMessage();
				Session::put('errors',$error_msg);
            }
        }
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
    }
?>