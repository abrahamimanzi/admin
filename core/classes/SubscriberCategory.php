<?php
/*
class SubscriberCategory
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
	}

    //USER CREATE AN ACCOUNT
	public function insert($fields = array()){
		if(!$this->_db->insert('subscriber_category', $fields)){
			throw new Exception("There was a problem creating an subscriber_category account.");
		}
	}

// USER DATA UPDATE
	public function update($fields = array(),$id = null){
		if(!$this->_db->update('subscriber_category',$id,$fields)){
			throw new Exception('There was a problem updating');
		}
	}

// USER DATA UPDATE
	public function delete($where){
		if(!$this->_db->delete('subscriber_category',$where)){
			throw new Exception('There was a problem deleting');
		}
	}
	
	// select	
	public function select($sql = null){
		$data = $this->_db->query("SELECT* FROM `subscriber_category` {$sql}");
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
*/
?> 


<?php
class SubscriberCategory
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
	}

    //USER CREATE AN ACCOUNT
	public function insert($fields = array()){
		if(!$this->_db->insert('subscriber_category', $fields)){
			throw new Exception("There was a problem creating an subscriber_category account.");
		}
	}

// USER DATA UPDATE
	public function update($fields = array(),$id = null){
		if(!$this->_db->update('subscriber_category',$id,$fields)){
			throw new Exception('There was a problem updating');
		}
	}

// USER DATA UPDATE
	public function delete($where){
		if(!$this->_db->delete('subscriber_category',$where)){
			throw new Exception('There was a problem deleting');
		}
	}
	
	// select	
	public function select($sql = null){
		$data = $this->_db->query("SELECT* FROM `subscriber_category` {$sql}");
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