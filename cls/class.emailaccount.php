<?php
class EmailAccount{
	const COLLECTION = 'emailaccount';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $emailaddress = '';
	public $ghichu = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(EmailAccount::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post'=> -1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('date_post'=> -1));	
	}

	public function get_id_by_emailaddress(){
		$query = array('emailaddress' => $this->emailaddress);
		$field = array('_id' => true);
		$result = $this->_collection->findOne($query, $field);
		if(isset($result['_id'])) return $result['_id'];
		return 0;
	}

	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function insert(){
		$query = array('emailaddress' => $this->emailaddress, 'ghichu' => $this->ghichu);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array('$set' => array('emailaddress' => $this->emailaddress, 'ghichu' => $this->ghichu));
	$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function count_all(){
		return $this->_collection->find()->count();
	}

	public function check_exists(){
		$query = array('emailaddress'=>$this->emailaddress);
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true; else return false;
	}
}
?>