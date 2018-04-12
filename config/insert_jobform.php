<?php
$fullname = data_in(Input::get('fullname','post'));
$phone = data_in(Input::get('phone','post'));
$email = data_in(Input::get('email','post'));
$message = data_in(Input::get('message','post'));
$job_ID = sanAsID(Input::get('job_ID','post'));

$jobOppClass = new JobOpp($job_ID);

$validate = new Validate();
$validation = $validate->check($_POST, array(
	'fullname' => array(
		'name' => 'Full Name',
		'string' => true,
		'required' => true,
		'min' => 2
	),
	'phone' => array(
		'name' => 'Telephone',
		'required' => true,
		'min' => 2
	),
	'email' => array(
		'name' => 'Email',
		'email' => true,
		'required' => true,
		'min' => 2
	),
	'job_ID' => array(
		'name' => 'Job position',
		'required' => true,
		'min' => 1
	),
	'message' => array(
		'name' => 'Headline Message',
		'required' => true
	)
));

if($jobOppClass->count()){
	if($validation->passed()){
		
		$file_form = 'cv';	
		if(isset($_FILES["$file_form"]['name']) && !empty($_FILES["$file_form"]['name'])){
			$_FILES["$file_form"]['type'] = strtolower($_FILES["$file_form"]['type']);
			$dir = $url.'/admin/client_data/files/';
			$fileName = $_FILES["$file_form"]["name"]; // The file name
			$fileExten = get_exten($fileName); // The file name
			if($fileExten == 'pdf'){
				// setting file's mysterious name
				$keep_orig = true;
				include 'admin/client_data/doc_upload'.PL;
				
				$cv_url = $original_file;
			}else{
                $form_error = true;
                Session::put('errors',"File Format not supported, make it a pdf file");
            }
		}else{
            $form_error = true;
            Session::put('errors',"Please attach your CV, the application form as well");
        }
		
		$file_form = 'appform';	
		if(isset($_FILES[$file_form]['name']) && !empty($_FILES["$file_form"]['name'])){
			$_FILES["$file_form"]['type'] = strtolower($_FILES["$file_form"]['type']);
			$dir = $url.'/admin/client_data/files/';
			$fileName = $_FILES["$file_form"]["name"]; // The file name
			$fileExten = get_exten($fileName); // The file name
			if($fileExten == 'pdf'){
				// setting file's mysterious name
				$keep_orig = true;
				include 'admin/client_data/doc_upload'.PL;
				
				$appform_url = $original_file;
			}else{
                $form_error = true;
                Session::put('errors',"File format not supported, make it a pdf file");
            }
		}else{
            $form_error = true;
            Session::put('errors',"Please attach the application form, your CV as well");
        }
		
		$jobFormClass = new JobForm();
		
		if($form_error == false){
            try{
                $jobFormClass->insert(array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $email,
                    'job_ID'	 => $job_ID,
                    'cv_url' => $cv_url,
                    'appform_url' => $appform_url,
                    'message' => $message,
                    'state' => 'Pending',
                    'created_date'	=> Config::get('time/temp')
                ));
				
                Session::put('success', 'The job has been recorded successfully!');
				Redirect::to('career');
				
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
}else{
	Session::put('errors','Sorry! The targeted job has expired or not available!');
}
?>