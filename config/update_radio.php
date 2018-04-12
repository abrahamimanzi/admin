<?php
if(Input::checkInput('RadioProfile','post','1')){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'radio_name' => array(
			'name' => 'Radio name',
			'required' => true,
			'min' => 2
		),
		'radio_freq' => array(
			'name' => 'Frequency',
			'required' => true
		),
		'description' => array(
			'name' => 'Description',
			'required' => true
		),
		'radio_color' => array(
			'name' => 'Color',
			'required' => true
		)
	));
	if($validation->passed()){
		$radioClass = new Radios();
		
		$radio_name = sanName(Input::get('radio_name','post'));
		$radio_freq = data_in(Input::get('radio_freq','post'));
		$description = data_in(Input::get('description','post'));
		$radio_color = data_in(Input::get('radio_color','post'));
		$stream_code = Input::get('stream_code','post');
		$facebooklink = Input::get('facebooklink','post');
		$twitterlink = Input::get('twitterlink','post');
		
		$temp = Config::get('time/temp');
		
		$profile_photo = $radio_data->profile;
			
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
			$radioClass->update(array(
				'radio_name' => $radio_name,
				'radio_freq'	 => $radio_freq,
				'description'	 => $description,
				'radio_color'	 => $radio_color,
				'stream_code' => $stream_code,
				'profile' => $profile_photo,
				'facebooklink' => $facebooklink,
				'twitterlink' => $twitterlink,
				'created_date'	=> Config::get('time/temp')
			),$radio_data->ID);
			
            Session::put('success', "<b>{$radio_data->radio_name}</b> Radio has been updated successfully!");
			
		}catch(Exeption $e){
			$form_error = true;
			Session::put('errors', $e->getMessage());
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
}

if(Input::checkInput('RadioPhotos','post','1')){
	
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'fullname' => array(
			'name' => 'Full name',
			'required' => true,
			'min' => 2
		),
		'title' => array(
			'name' => 'Photo title',
			'required' => true
		),
		'details' => array(
			'name' => 'Photo details',
			'required' => true,
			'min' => 2
		)
	));
	if($validation->passed()){
		
		$radioPhotosClass = new RadioPhotos();
		$radio_ID = $radio_data->ID;
		$fullname = data_in(Input::get('fullname','post'));
		$twitterlink = data_in(Input::get('twitterlink','post'));
		$facebooklink = data_in(Input::get('facebooklink','post'));
		$title = data_in(Input::get('title','post'));
		$details = data_in(Input::get('details','post'));
		
		$temp = Config::get('time/temp');
		
		$profile_av = false;
			
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
					$profile_av = true;
				}
			}
		}
		
		if($profile_av){
			try{
				$radioPhotosClass->insert(array(
					'radio_ID' => $radio_data->ID,
					'title' => $title,
					'fullname' => $fullname,
					'twitterlink' => $twitterlink,
					'facebooklink' => $facebooklink,
					'details' => $details,
					'url' => $profile_photo,
					'created_date'	=> Config::get('time/temp')
				));
				Session::put('success', "<b>{$radio_data->radio_name}</b> Photo has been uploaded!");
				
				
			}catch(Exeption $e){
				$form_error = true;
				Session::put('errors', $e->getMessage());
			}
		}else{
			$form_error = true;
			$error_msg = "Please select the image";
			Session::put('errors',$error_msg);
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
}
?>