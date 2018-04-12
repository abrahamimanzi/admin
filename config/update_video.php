<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'name' => 'Title',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'category' => array(
			'name' => 'Category',
			'required' => true
		),
		'description' => array(
			'name' => 'Description',
			'required' => false,
		)
	));
	if($validation->passed()){
	
		$title = sanName(Input::get('title','post'));
		
		$category = data_in(Input::get('category','post'));
		$description = data_in(Input::get('description','post'));
		
		$temp = Config::get('time/temp');
		
		$featured_photo = $video_data->preview;
		
		if(isset($_FILES['featuredImage']['name']) && !empty($_FILES['featuredImage']['name'])){
			$_FILES['featuredImage']['type'] = strtolower($_FILES['featuredImage']['type']);
			$dir = 'media_data/cover_photo/';
			if($_FILES['featuredImage']['type'] == 'image/png'
				|| $_FILES['featuredImage']['type'] == 'image/jpg'
				|| $_FILES['featuredImage']['type'] == 'image/gif'
				|| $_FILES['featuredImage']['type'] == 'image/jpeg'
				|| $_FILES['featuredImage']['type'] == 'image/pjpeg')
			{
				
				include_once('media_data/upload_cover_photo'.PL);
				$featured_photo = $resized_file;
				@unlink($article_data->featured_photo);
			}else{
				$form_error = true;
				Session::put('errors',"Featured photto File not supported");
			}
		}
		
	
		if($form_error == false){
			try{
				$videoClass->update(array(
					'title' => $title,
					'category' => $category,
					'description' => $description,
					'preview' => $featured_photo
				),$video_ID);

				Session::put('success', 'Video successfully updated!');
				
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