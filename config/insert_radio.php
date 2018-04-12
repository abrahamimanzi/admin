

<?php
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
		$radio_color = data_in(Input::get('radio_color','post'));
		$description = data_in(Input::get('description','post'));
		$stream_code = Input::get('stream_code','post');
		$facebooklink = Input::get('facebooklink','post');
		$twitterlink = Input::get('twitterlink','post');
		
		$temp = Config::get('time/temp');
		
		$profile_photo = '';
			
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
			
				if(@$resized_file){
					$profile_photo = $original_file;
				}
			}else{
                $form_error = true;
                Session::put('errors',"Photo format not supported, choose a Logo photo");
            }
		}else{
            $form_error = true;
            Session::put('errors',"Please choose a Logo photo");
        }
        
        if($form_error == false){
            try{
                $radioClass->insert(array(
                    'radio_name' => $radio_name,
                    'radio_freq'	 => $radio_freq,
                    'description'	 => $description,
                    'radio_color'	 => $radio_color,
                    'stream_code' => $stream_code,
                    'profile' => $profile_photo,
                    'facebooklink' => $facebooklink,
                    'twitterlink' => $twitterlink,
                    'created_date'	=> Config::get('time/temp')
                ));

                Session::put('success','Radio has been recorded successfully!');
             

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