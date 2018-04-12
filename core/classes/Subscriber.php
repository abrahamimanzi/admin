<?php
class Subscriber
{
	private $_db,
			$_data,
			$_count = 0,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
			$_errors = array();
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/subscriber');
		$this->_cookieName = Config::get('remember/subscriber');
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				if($this->find_user($user)){
					$this->_isLoggedIn = true;
				}else{
				 //logout
				}
			}
		}else{
			$this->find_user($user);
		}
	}

    //USER CREATE AN ACCOUNT
	public function insert($fields = array()){
		if(!$this->_db->insert('subscriber', $fields)){
			throw new Exception("There was a problem creating an subscriber account.");
		}
	}

// USER DATA UPDATE
	public function update($fields = array(),$id = null){
		if(!$this->_db->update('subscriber',$id,$fields)){
			throw new Exception('There was a problem updating');
		}
	}
	
// USER DATA UPDATE
	public function recoverPassword($fields = array(),$id = null){
		
		if(!$id && $this->isLoggedIn()){
			$id = $this->data()->ID;
		}else{
			//Admin Of A group Came Update
		}
		
		if(!$this->_db->update('subscriber',$id,$fields)){
			throw new Exception('There was a problem updating');
		}
	}

// FIND USER
	public function find($user = null){
		if($user){
			$hit = false;
			if(is_numeric($user)){
				$field = 'ID';
				$data = $this->_db->get('subscriber', array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}else if(filter_var($user, FILTER_VALIDATE_EMAIL) == true){
				$field = 'email';
				$data = $this->_db->get('subscriber', array($field, '=', $user),$limit);
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}else{
				$subscriberClass = new Subscriber();
				$subscriberClass->select("WHERE `username` = '{$user}' LIMIT 1");
				if($subscriberClass->count()){
					$this->_data = $subscriberClass->first();
					$hit = true;
				}
			}
			
			if($hit == false){
				if($this->findUserByPhone($user)){
					return true;
				}
			}else{
				return true;
			}
		}
		return false;
	}
	
// FIND USER
	public function find_user($user = null,$fields = array()){
		$table = "subscriber";
		if($user){
			$hit = false;
			if(is_numeric($user)){
				$field = 'ID';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}elseif(filter_var($user, FILTER_VALIDATE_EMAIL) == true){
				$field = 'email';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
                    $hit = true;
				}
			}else{
				$field = 'username';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}
			
			if($hit == true){
				return true;
			}
		}
		return false;
	}
	
// FIND USER
	public function findUserByPhone($user = null){
		if($user){
			$subscriberClass = new Subscriber();
			$subscriberClass->select("WHERE `telephone`='{$user}' LIMIT 1");
			if($subscriberClass->count()){
				$this->_data = $subscriberClass->first();
				return true;
			}
		}
		return false;
	}
    
    public function isCurrentSession($user_ID){
        $id = Session::get(Config::get('session/subscriber'));
        if($user_ID == $id ){
            return true;
        }
        return false;
        
    }
	
	// select	
	public function select($sql = null){
		$data = $this->_db->query("SELECT* FROM `subscriber` {$sql}");
		if($data->count()){
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}
	// Select	
	public function selectQuery($sql,$params=array()){
		$data = $this->_db->query($sql,$params);
		if($data->count()){
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}
	
// USER LOGIN
	public function login($username = null, $password = null, $remember = false){
		$time = Config::get('time/seconds');
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->ID);
			$userID = $this->data()->ID;
			
			$subscriberTokenClass = new SubscriberToken();
            $subscriberTokenClass->select(array('ID','user_ID'),"WHERE `user_ID`= ? ",array($userID));
            if(!$subscriberTokenClass->count()){
                $token_hash = Hash::unique();
                $subscriberTokenClass->insert(array('user_ID'=>$userID,'hash'=>$token_hash));
            }
		}else{
			$user = $this->find_user($username);
			if($user){
				if($this->data()->password === Hash::make($password, $this->data()->salt)){
					Session::put($this->_sessionName,$this->data()->ID);
					
					$userID = $this->data()->ID;
					
					$subscriberTokenClass = new SubscriberToken();
                    $subscriberTokenClass->select(array('ID','user_ID'),"WHERE `user_ID`= ? ",array($userID));
                    if(!$subscriberTokenClass->count()){
                        $token_hash = Hash::unique();
                        $subscriberTokenClass->insert(array('user_ID'=>$userID,'hash'=>$token_hash));
                    }
					// Set last login
					
					return true;
				
					$this->addError('Blocked account');
					return 'Denied';
				}else{
					$this->addError('Wrong Password');
					return 'password';
				}
			}else{
				$this->addError('That username doesn\'t exist');
				return 'username';
			}
		}
		return false;
	}

	
	public function exists(){
		return (!empty($this->_data))? true : false;
	}
	
	// SENDING EMAIL
	public function emailTo($from,$to,$subject='',$body='',$headers=''){
		if(Validate::valEmail($from)===true){
			if(Validate::valEmail($to)===true){
				$fname = '';
				$lname = '';
				if($this->find($from)==true){
					$user_data = $this->data();
					$fname = $user_data->firstname;
					$lname = $user_data->lastname;
				}
				if($this->find($to)==true){
					$user_data_to = $this->data();
					$fname_to = $user_data_to->firstname;
					$lname_to = $user_data_to->lastname;
				}
				
				
				$message = $body;
				$email_body = $message;

				//SENDING MESSAGE
				@mail($to, $subject,$email_body, $headers);
			}
		}
	}	


// USER LOGOUT
	public function logout(){
		$sessionName = Config::get('session/subscriber');
		$cookieName = Config::get('remember/subscriber');
		if(Session::exists($sessionName)){
			$user_ID = Session::get($this->_sessionName);
			$this->update(array('account_session'=>'0'));
			Session::delete($this->_sessionName);
			$subscriberTokenClass = new SubscriberToken();
			$subscriberTokenClass->select(array('ID','user_ID'),"WHERE `user_ID`= ? ",array($user_ID));
			if($subscriberTokenClass->count()){
				$usertoken_data = $subscriberTokenClass->first();
				$subscriberTokenClass->delete($usertoken_data->ID);
			}
		}
		Cookie::delete($this->_cookieName);
	}
	
// CHECK USER LOGGED IN
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}

// DATA COLLECT
	public function data(){
		return $this->_data;
	}
// first
	public function first(){
		$data = $this->data();
		if(isset($data[0])){
			return $data[0];
		}
		return '';
	}
	
// DATA COUNT
	public function count(){
		return $this->_count;
	}
	
// ADD ERRORS NOTIF
	private function addError($error){
		$this->_errors[] = $error;
	}
// ERROR COLLECT
	public function errors(){
		return $this->_errors;
	} 
}
?> 