<?php
class LoaiVanBan{
	const COLLECTION = 'loaivanban';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $ten = '';
	public $id_parent = '';
	public $date_post = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(LoaiVanBan::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('date_post'=> 1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('date_post'=> 1));	
	}
	public function insert(){
		return $this->_collection->insert(array('ten'=>$this->ten, 'id_parent' => $this->id_parent ? new MongoId($this->id_parent) : '', 'date_post' => new MongoDate()));
	}
	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}
	public function delete_childs(){
		return $this->_collection->remove(array('id_parent'=> new MongoId($this->id_parent)));	
	}
	public function edit(){
		return $this->_collection->update(array('_id'=> new MongoId($this->id)), array('$set' => array('ten'=> $this->ten, 'id_parent' => $this->id_parent ? new MongoId($this->id_parent) : '')));
	}
	public function get_parent_name(){
		$query = array('_id' => new MongoId($this->id_parent), 'id_parent' => '');
		$result = $this->_collection->findOne($query);
		if($result) return $result['ten'];
		return null;
	}
	public function check_exists(){
		$query = array('ten'=>trim($this->ten));
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true; else return false;
	}
}
?>