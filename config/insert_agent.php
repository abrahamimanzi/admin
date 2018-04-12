

<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'fullname' => array(
			'name' => 'Full Name',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'job_title' => array(
			'name' => 'Group',
			'required' => true
		),
		'email' => array(
			'name' => 'Email',
			'required' => true,
			'email' => true
		),
		'description' => array(
			'name' => 'Description',
			'required' => true
		)
	));
	if($validation->passed()){
		$user = new Agent();
		
		
		$fullname = sanName(Input::get('fullname','post'));
		$email = data_in(Input::get('email','post'));
		$description = data_in(Input::get('description','post'));
		$job_title = data_in(Input::get('job_title','post'));
		$facebooklink = data_in(Input::get('facebooklink','post'));
		$twitterlink = data_in(Input::get('twitterlink','post'));
		
		$temp = Config::get('time/temp');
		
		$profile_photo = '';
			
		if(isset($_FILES['profilePhoto']['name']) && !empty($_FILES['profilePhoto']['name'])){
			$_FILES['profilePhoto']['type'] = strtolower($_FILES['profilePhoto']['type']);
			$dir = $url.'/admin/user_data/profile/';
			if($_FILES['profilePhoto']['type'] == 'image/png'
				|| $_FILES['profilePhoto']['type'] == 'image/jpg'
				|| $_FILES['profilePhoto']['type'] == 'image/gif'
				|| $_FILES['profilePhoto']['type'] == 'image/jpeg'
				|| $_FILES['profilePhoto']['type'] == 'image/pjpeg')
			{
				// setting file's mysterious name
				$keep_orig = true;
				include_once('user_data/image_upload.php');
			
				if(@$resized_file){
					$profile_photo = $original_file;
				}
			}else{
                $form_error = true;
                Session::put('errors',"Photo format not supported, choose a Profile photo");
            }
		}else{
            $form_error = true;
            Session::put('errors',"Please choose a Profile photo");
        }
        
        if($form_error == false){
            try{
                $user->insert(array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'job_title'	 => $job_title,
                    'description' => $description,
                    'profile' => $profile_photo,
                    'facebooklink' => $facebooklink,
                    'twitterlink' => $twitterlink,
                    'state' => 'Activated',
                    'created_date'	=> Config::get('time/temp')
                ));

                $db = DB::getInstance();
                $sql_data = $db->get('agents', array('email', '=', $email),1);

                if($sql_data->count()){
                    Session::put('success', 'The agent has been recorded successfully!');
                }

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