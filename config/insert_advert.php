<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'name' => 'Title',
			'string' => true,
			'required' => true
		),
		'position' => array(
			'name' => 'Position',
			'required' => true
		)
	));
	
	//list($width, $height) = getimagesize('path_to_image');
	
	if($validation->passed()){
			$advertClass = new Adverts();
			
			$title = sanName(Input::get('title','post'));
			$position = data_in(Input::get('position','post'));
			
			$state = "Shelved";
			$state = data_in(Input::get('state','post'));
			
			$link = addslashes(Input::get('url','post'));
            
			$banner_photo = '';
			if(isset($_FILES['banner_photo']['name']) && !empty($_FILES['banner_photo']['name'])){
				$_FILES['banner_photo']['type'] = strtolower($_FILES['banner_photo']['type']);
                
				$dir = 'advert_data/images/';
				if($_FILES['banner_photo']['type'] == 'image/png'
					|| $_FILES['banner_photo']['type'] == 'image/jpg'
					|| $_FILES['banner_photo']['type'] == 'image/gif'
					|| $_FILES['banner_photo']['type'] == 'image/jpeg'
					|| $_FILES['banner_photo']['type'] == 'image/pjpeg')
				{
					
					$filename = substr(md5(date('YmdHis')),-5,5).'.jpg';
					$file = $dir.'thumb_'.$filename;
					
					include_once('advert_data/image_upload.php');
					
					$banner_photo = $original_file;
				}else{
					$form_error = true;
					Session::put('errors',"Banner File not supported");
				}
			}else{
				$form_error = true;
				Session::put('errors',"Please choose a Banner");
			}
			
			
			$user_ID = $session_user_data->ID;
			
			$temp = Config::get('time/temp');
			
			if($form_error == false){
				try{
					if(($position == "Top" || $position == "Bottom" || $position == "Carousel"
						|| $position == "Page_header" || $position == "Page_header1" 
						|| $position == "Page_header2" || $position == "Page_header3"
						|| $position == "Page_header4" || $position == "Page_header5"
						|| $position == "Videorow" || $position == "Videorow1") && $state=='Published'){
						$db = DB::getInstance();
						$db->query("UPDATE `adverts` SET `state`='Shelved' WHERE `position`='{$position}' AND `state`='Published'");
					}
					$advertClass->insert(array(
						'title' => $title,
						'path' => $banner_photo,
						'position'=>$position,
						'url'=>$link,
						'state'=>$state,
						'user_ID'=>$session_user_ID,
						'created_date'=>$temp
					));
					Session::put('success', 'Advert successfully added!');
					
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