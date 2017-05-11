<?php
class DonViSoanThao{
	const COLLECTION = 'donvisoanthao';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $ten = '';
	public $date_post = '';

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(DonViSoanThao::COLLECTION);
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
		$query = array('code' => $this->code,
					'ten' => $this->ten,
					'date_post' => new MongoDate());
		return $this->_collection->insert($query);
	}
	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}

	public function edit(){
		$query = array('$set'=> array('ten' => $this->ten));
		return $this->_collection->update(array('_id'=> new MongoId($this->id)), $query);
	}
	public function check_exists(){
		$query = array('ten'=>$this->ten);
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true; else return false;
	}
}
?>
