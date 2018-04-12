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
		),
		'email' => array(
			'name' => 'Email',
			'required' => true,
			'email' => true,
			'unique' => 'users'
		)
	));
	if($validation->passed()){
		$user = new User();
		
		$fullname = sanName(Input::get('fullname','post'));
		$email = data_in(Input::get('email','post'));
		$gender = data_in(Input::get('gender','post'));
		$groups = data_in(Input::get('groups','post'));
		$pre_username = sanUsername(remSpaces(Input::get('fullname','post')));
		$username = $validate->autoUniqueMaker('users','username',$pre_username);
		
		$temp = Config::get('time/temp');
		$salt = Hash::salt(32);
		$n = $temp+9999999;
		$generate_password = substr(rand(999,$n),-6,6);
		$password = Hash::make($generate_password,$salt);
		
		$profile_photo = '';
			
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
				$filename = substr(md5(date('YmdHis')),-5,5).'.jpg';
				$file = $dir.'thumb_'.$filename;
				include_once('user_data/image_upload.php');
			
				if(@$resized_file){
					$profile_photo = $resized_file;
				}
			}
		}
		try{
			$user->insert(array(
				'fullname' => $fullname,
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'groups'	 => $groups,
				'salt' 	 => $salt,
				'gender' => $gender,
				'profile' => $profile_photo,
				'created_date'	 => Config::get('time/temp'),
				'default_password'	 => $generate_password
			));
			
			$db = DB::getInstance();
			$sql_data = $db->get('users', array('email', '=', $email),1);
			
			if($sql_data->count()){
				$user_ID = $sql_data->first()->ID;
				
				// login
				$user = new User($user_ID);
				$user_data = $user->data();
				$user_ID = $user_data->ID;
				$to = $user_data->email;
				$from = 'support@rba.co.rw';
				$subject='Welcome on RBA';
				$heading = $fullname. '('.$to.')';
				$headers = 'FROM: '. strip_tags($from). "\r\n";
				$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$messageText = '
					<h3 style="color: #104080"><center>Your account has been succsessfully created</center></h3>
					<p style="font-size: 15px">
						You can login your account using this default password <b>'.$generate_password.'</b><br/></br/>
						
						<b>NB</b>: <i>This password has been generated for you by default. We strongly recommend you to login your account and change it as soon as posible<br/>
								For more clarification contact your adimistrator.
						</i>
					</p>
				';
				Functions::sendEmail($user_ID,$from,$to,$subject,$headers,$messageText,$heading);
				Session::put('success', 'The '.$groups.' account has been created successfully!');
			}
			
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