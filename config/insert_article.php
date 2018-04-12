<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'name' => 'Title',
			'string' => true,
			'required' => true,
			'min' => 2
		),
		'briefing' => array(
			'name' => 'Briefing field',
			'string' => true,
			'required' => true
		)
	));
	if($validation->passed()){
		$slider = sanName(Input::get('slider','post'));
		if((!isset($_POST['categories']) || !count( $_POST['categories'])) && $slider){
			$form_error = true;
			Session::put('errors',"Please check categories for this article");
		}else{
			$category_list = @$_POST['categories'];
			$lang_list = $_POST['langs'];
			
			$lang_sql = '|'.implode('|',$lang_list).'|';
			
			$articleClass = new Article();
			
			$title = trim(data_in(Input::get('title','post')));
			
			$url_title = trim(Input::get('title','post'));
			$url_title = preg_replace('!\s+!', '-', $url_title);// Removes multisplaces.
			$url_title = preg_replace('/[^A-Za-z0-9\-]/', '', $url_title); // Removes special chars.
			$url_title = $validate->autoUniqueMaker('article','url_title',$url_title);
			
			$briefing = data_in(Input::get('briefing','post'));
			$field = data_in(Input::get('field','post'));
			$body = Input::get('body','post');
			$featured_photo = '';
			if(isset($_FILES['featuredImage']['name']) && !empty($_FILES['featuredImage']['name'])){
				$_FILES['featuredImage']['type'] = strtolower($_FILES['featuredImage']['type']);
				$dir = 'media_data/cover_photo/';
				if($_FILES['featuredImage']['type'] == 'image/png'
					|| $_FILES['featuredImage']['type'] == 'image/jpg'
					|| $_FILES['featuredImage']['type'] == 'image/gif'
					|| $_FILES['featuredImage']['type'] == 'image/jpeg'
					|| $_FILES['featuredImage']['type'] == 'image/pjpeg')
				{
					// setting file's mysterious name
					$filename = substr(md5(date('YmdHis')),-5,5).'.jpg';
					$file = $dir.'thumb_'.$filename;
					include_once('media_data/upload_cover_photo.php');
					
					$featured_photo = $original_file;
				}else{
					$form_error = true;
					Session::put('errors',"Featured photo File not supported");
				}
			}else{
				if(count($category_list)==1 && $category_list[0] == 5){
					
				}else{
					$form_error = true;
					Session::put('errors',"Featured photo field is empty");
				}
			}
			
			$user_ID = $session_user_data->ID;
			
			$temp = Config::get('time/temp');
			
			if($form_error == false){
				try{
					$articleClass->insert(array(
						'title' => $title,
						'field' => $field,
						'slider' => $slider,
						'url_title' => $url_title,
						'briefing' => $briefing,
						'body' => $body,
						'lang' => $lang_sql,
						'featured_photo' => $featured_photo,
						'state' => "Shelved",
						'user_ID' => $user_ID,
						'created_date'	 => Config::get('time/temp')
					));
					$articleClass->getCreated($session_user_ID);
					if($articleClass->count()){
						$articleCategClass = new ArticleCateg();
						$article_data = $articleClass->first();
						$article_ID = $article_data->ID;
						if(count($category_list)){
							foreach($category_list as $category_ID){
								$articleCategClass->insert(array(
									'article_ID' => $article_ID,
									'category_ID' => $category_ID,
									'created_date' => $article_data->created_date
								));
							}
						}
					}
					Session::put('success', 'Article successfully recorded!');
					
				}catch(Exeption $e){
					$form_error = true;
					$error_msg = $e->getMessage();
					Session::put('errors',$error_msg);
				}
			}
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
?>