<?php
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'title' => array(
			'name' => 'Title',
			'string' => true,
			'required' => true,
			'min' => 2
		)
	));
	if($validation->passed()){
		$docsClass = new Docs();
		
		$title = data_in(Input::get('title','post'));
		$details = data_in(Input::get('details','post'));
		
		$temp = Config::get('time/temp');
        
		$docs_url = $docs_data->path;
		$docs_size =  $docs_data->size;
		
		$file_form = 'docs';	
		if(isset($_FILES["$file_form"]['name']) && !empty($_FILES["$file_form"]['name'])){
			$_FILES["$file_form"]['type'] = strtolower($_FILES["$file_form"]['type']);
			$fileName = $_FILES["$file_form"]["name"]; // The file name
			$fileExten = get_exten($fileName); // The file name
			$allow_photo = in_array($fileExten,FileManager::getAllowed('photo'));
			$allow_doc = in_array($fileExten,FileManager::getAllowed('doc'));
			$allow_video = in_array($fileExten,FileManager::getAllowed('video'));
			$allow_audio = in_array($fileExten,FileManager::getAllowed('audio'));
			if($allow_doc || $allow_audio || $allow_video || $allow_photo){
				// setting file's mysterious name
				$keep_orig = true;
				$doc_folder = '';
				if($allow_doc){
					$doc_folder = '/doc';
				}elseif($allow_photo){
					$doc_folder = '/img';
				}elseif($allow_video){
					$doc_folder = '/video';
				}elseif($allow_audio){
					$doc_folder = '/audio';
				}
				$keep_orig = true;
				include 'official_data/doc_upload'.PL;
				
				@unlink($docs_url);
				$docs_url = $original_file;
				$docs_size = $_FILES["$file_form"]['size'];
			}else{
                $form_error = true;
                Session::put('errors',"File Format not supported, Contact the admin");
            }
		}
		
		
        if($form_error == false){
            try{
                $docsClass->update(array(
                    'title' => $title,
                    'details' => $details,
                    'path' => addslashes($docs_url),
                    'size' => $docs_size,
                ),$docs_data->ID);
                Session::put('success', 'The Docs has been recorded successfully!');
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