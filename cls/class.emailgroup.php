<?php
class EmailGroup{
	const COLLECTION = 'emailgroup';
	private $_mongo;
	private $_collection;

	public $id = '';
	public $id_group = '';
	public $id_email = array();
	public $ghichu = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(EmailGroup::COLLECTION);
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

	public function get_id_group_by_id_email($id_email){
		$query = array('id_email' => $id_email);
		return $this->_collection->find($query);
	}

	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function insert(){
		$query = array('id_group' => new MongoId($this->id_group),
						'id_email' => $this->id_email,
						'ghichu' => $this->ghichu);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$query = array('$set' => array('id_group' => new MongoId($this->id_group),
						'id_email' => $this->id_email,
						'ghichu' => $this->ghichu));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function edit_except_group(){
		$query = array('$set' => array('id_email' => $this->id_email,
						'ghichu' => $this->ghichu));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function check_exists(){
		$query = array('id_group' => new MongoId($this->id_group));
		$field = array('_id' => true);
		$result = $this->_collection->findOne($query, $field);
		if(isset($result['_id']) && $result['_id']) return true;
		else return false;
	}

	public function check_dm_email($id){
		$query = array('$or' => array(array('id_group' => new MongoId($id)), array('id_email' => $id)));
		$field = array('_id' => true);
		$result = $this->_collection->findOne($query, $field);
		if(isset($result['_id']) && $result['_id']) return true;
		else return false;	
	}
}

?>