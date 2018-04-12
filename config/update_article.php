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
		$category_list = @$_POST['categories'];
		$slider = sanName(Input::get('slider','post'));
		if(!count($category_list) && empty($slider)){
			$form_error = true;
			Session::put('errors',"Please check categories for the article");
		}else{
			
			$lang_list = $_POST['langs'];
			$lang_sql = '|'.implode('|',$lang_list).'|';
			
			$title = trim(data_in(Input::get('title','post')));
			
			$briefing = data_in(Input::get('briefing','post'));
			$field = data_in(Input::get('field','post'));
			$body = Input::get('body','post');
			$user_ID = $session_user_data->ID;
			$temp = Config::get('time/temp');
			
			$featured_photo = $article_data->featured_photo;
			
			if(isset($_FILES['featuredImage']['name']) && !empty($_FILES['featuredImage']['name'])){
				$_FILES['featuredImage']['type'] = strtolower($_FILES['featuredImage']['type']);
				$dir = 'media_data/cover_photo/';
				if($_FILES['featuredImage']['type'] == 'image/png'
					|| $_FILES['featuredImage']['type'] == 'image/jpg'
					|| $_FILES['featuredImage']['type'] == 'image/gif'
					|| $_FILES['featuredImage']['type'] == 'image/jpeg'
					|| $_FILES['featuredImage']['type'] == 'image/pjpeg')
				{
					
					$keep_orig = true;
					include_once('media_data/upload_cover_photo.php');
					
					@unlink($article_data->featured_photo);
					
					$featured_photo = $original_file;
				}else{
					if(count($category_list)==1 && $nat_categ['Announcement'] == $category_list[0]){
					
					}else{
						$form_error = true;
						Session::put('errors',"Please check categories for the article");
					}
				}
			}else{
				if(count($category_list)==1 && $category_list[0] == 5){
					
				}elseif(empty($featured_photo)){
					$form_error = true;
					Session::put('errors',"Featured photo field is empty");
				}
			}
			
		
			if($form_error == false){
				try{
					$articleClass->update(array(
						'title' => $title,
						'field' => $field,
						'body' => $body,
						'slider' => $slider,
						'lang' => $lang_sql,
						'briefing' => $briefing,
						'updated_date' 	=> $temp,
						'featured_photo' => $featured_photo
					),$article_ID);
					
					$articleCategClass = new ArticleCateg();
					$articleCategClass->remove_article($article_ID);
					if(count($category_list)){
						foreach($category_list as $category_ID){
							$articleCategClass->insert(array(
								'article_ID' => $article_ID,
								'category_ID' => $category_ID,
								'created_date' => $article_data->created_date
							));
						}
					}
					Session::put('success', 'Article successfully updated!');
					
				}catch(Exeption $e){
					$form_error = true;
					Session::put('errors', $e->getMessage());
				}
			}
		}
	}else{
		$form_error = true;
		$error_msg = ul_array($validation->errors());
		Session::put('errors',$error_msg);
	}
?>