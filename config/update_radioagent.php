<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'fullname' => array(
			'name' => 'Full Name',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'title' => array(
			'name' => 'Group',
			'required' => true
		),
		'details' => array(
			'name' => 'Details',
			'required' => true
		)
	));
	if($validation->passed()){
		$user = new RadioPhotos();
		
		
		$fullname = sanName(Input::get('fullname','post'));
		$email = data_in(Input::get('email','post'));
		$details = data_in(Input::get('details','post'));
		$title = data_in(Input::get('title','post'));
        
		$facebooklink = data_in(Input::get('facebooklink','post'));
		$twitterlink = data_in(Input::get('twitterlink','post'));
		
		$temp = Config::get('time/temp');
		
		$profile_photo = $agent_data->url;
			
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
			
				if(@$original_file){
					$profile_photo = $original_file;
				}
			}
		}
		try{
			$user->update(array(
				'fullname' => $fullname,
				'title'	 => $title,
				'details' => $details,
				'url' => $profile_photo,
                'facebooklink' => $facebooklink,
                'twitterlink' => $twitterlink,
				'created_date'	 => Config::get('time/temp')
			),$agent_data->ID);
			
            
            Session::flash('success', 'The Radio agent has been updated successfully!');
			
			
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