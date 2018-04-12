<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'fullname' => array(
			'name' => 'Full Name',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'groups' => array(
			'name' => 'Group',
			'required' => true
		)
	));
	if($validation->passed()){
		$user = new User();
		
		$password = $user_data->password;
		$salt = $user_data->salt;
		
		$form_error = false;
		$password_txt = Input::get('password','post');
		if(!empty($password_txt)){
			if(Input::get('password','post') != Input::get('repassword','post')){
				$form_error = true;
				Session::put('errors',"Entered password doesn't mutch");
			}else{
				if(strlen(Input::get('password','post')) <6){
					$form_error = true;
					Session::put('errors',"Password must have at leat 6 different charactors");
				}else{
					if(valid_pass(Input::get('password','post'))){
						$salt = Hash::salt(32);
						$passwordText = Input::get('password','post');
						$password = Hash::make($passwordText,$salt);
					}else{
						$form_error = true;
					}
				}
			}
		}
		
		$fullname = sanName(Input::get('fullname','post'));
		$gender = data_in(Input::get('gender','post'));
		$groups = data_in(Input::get('groups','post'));
		
		$temp = Config::get('time/temp');
		
		$profile_photo = $user_data->profile;
		
		if($form_error == false){		
			if(isset($_FILES['profilePhoto']['name']) && !empty($_FILES['profilePhoto']['name'])){
				$_FILES['profilePhoto']['type'] = strtolower($_FILES['profilePhoto']['type']);
				$dir = 'user_data/profile/';
				if($_FILES['profilePhoto']['type'] == 'image/png'
					|| $_FILES['profilePhoto']['type'] == 'image/jpg'
					|| $_FILES['profilePhoto']['type'] == 'image/gif'
					|| $_FILES['profilePhoto']['type'] == 'image/jpeg'
					|| $_FILES['profilePhoto']['type'] == 'image/pjpeg')
				{
					include_once('user_data/image_upload.php');
					
					if(@$resized_file){
						$profile_photo = $resized_file;
					}
				}
			}
	
			try{
				$user->update(array(
					'fullname' 	=> $fullname,
					'groups'	=> $groups,
					'gender' 	=> $gender,
					'password' 	=> $password,
					'salt' 		=> $salt,
					'profile' 	=> $profile_photo
				),$user_ID);
				
				Session::put('success', 'User account successfully updated!');
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