

<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'address' => array(
			'name' => ' Address',
			'required' => true
		),
		'email' => array(
			'name' => 'Email',
			'required' => true,
			'email' => true
		),
		'name' => array(
			'name' => 'Name',
			'required' => true
		),
		'description' => array(
			'name' => 'Description',
			'required' => true
		),
		'mission' => array(
			'name' => 'Mission'
		),
		'vision' => array(
			'name' => 'Vision'
		),
		'work_motiv' => array(
			'name' => 'Work motivation'
		),
		'teamword' => array(
			'name' => 'Team word'
		)
	));
	if($validation->passed()){
		$user = new About();
		
		
		$name = data_in(Input::get('name','post'));
		$email = data_in(Input::get('email','post'));
		$description = Input::get('description','post');
		$mission = Input::get('mission','post');
		$vision = Input::get('vision','post');
		$work_motiv = Input::get('work_motiv','post');
		$teamword = data_in(Input::get('teamword','post'));
		$address = data_in(Input::get('address','post'));
        
		$facebooklink = data_in(Input::get('facebooklink','post'));
		$twitterlink = data_in(Input::get('twitterlink','post'));
		$youtubelink = data_in(Input::get('youtubelink','post'));
		$tweets = Input::get('tweets','post');
		
		$temp = Config::get('time/temp');
		
		$profile_photo = $about_data->profile;
			
		if(isset($_FILES['profilePhoto']['name']) && !empty($_FILES['profilePhoto']['name'])){
			$_FILES['profilePhoto']['type'] = strtolower($_FILES['profilePhoto']['type']);
			$dir = 'user_data/profile/';
			if($_FILES['profilePhoto']['type'] == 'image/png'
				|| $_FILES['profilePhoto']['type'] == 'image/jpg'
				|| $_FILES['profilePhoto']['type'] == 'image/gif'
				|| $_FILES['profilePhoto']['type'] == 'image/jpeg'
				|| $_FILES['profilePhoto']['type'] == 'image/pjpeg')
			{
				// setting file's mysterious name
				$keep_orig = true;
				include_once('user_data/image_upload.php');
			
				if(@$original_file){
					$profile_photo = $original_file;
				}
			}
		}
		try{
			$user->update(array(
				'address' => $address,
				'name' => $name,
				'email' => $email,
				'description' => $description,
				'mission' => $mission,
				'vision' => $vision,
				'work_motiv' => $work_motiv,
				'teamword' => $teamword,
				'profile' => $profile_photo,
                'facebooklink' => $facebooklink,
                'twitterlink' => $twitterlink,
                'youtubelink' => $youtubelink,
                'tweets' => $tweets,
				'created_date'	 => Config::get('time/temp')
			),$about_data->ID);
			
            
            Session::put('success', 'Changes has been updated successfully!');
			
			
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