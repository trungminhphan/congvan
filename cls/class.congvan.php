<?php
class CongVan{
	const COLLECTION = 'congvan';
	private $_mongo;
	private $_collection;
	public $id = '';
	public $id_loaicongvan = '';
	public $id_donvisoanthao = '';
	public $linhvuc = '';
	public $trichyeu = '' ;
	public $socongvan = '';
	public $sothutu = '';
	public $ngayky = '';
	public $ngaydiden = '';
	public $thoihanbaocao = ''; 
	public $canbobaocao = '';
	public $dabaocao = 0; //0 chua bao cao, 1 - da bao cao.
	public $cacvanbancolienquan = '';
	public $ghihchu = '';
	public $emaillist = array(); //id_emailaccount, view [0,1];
	public $dinhkem = '' ;
	public $date_post = '';
	public $id_user = '';
	public $public = 0;

	public function __construct(){
		$this->_mongo = DBConnect::init();
		$this->_collection = $this->_mongo->getCollection(CongVan::COLLECTION);
	}
	public function get_one(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id)));
	}
	public function get_all_list(){
		return $this->_collection->find()->sort(array('ngaydiden'=> -1));
	}
	public function get_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ngaydiden'=> -1));	
	}
	public function get_list_baocao($condition){
		return $this->_collection->find($condition)->sort(array('dabaocao'=> 1));	
	}
	public function updatedId(){
		$query = array('$set' => array('_id' => new MongoId('58c6562442f764b079aa1d18')));
		$condition = array('socongvan' => '283/HĐND-TT');
		return $this->_collection->update($condition, $query);
	}
	public function get_vanbanlienquan(){
		$query = array('_id' => new MongoId($this->id));
		$fields = array('_id' => true, 'socongvan' => true, 'trichyeu' => true);
		return $this->_collection->findOne($query, $fields);
	}
	public function delete(){
		return $this->_collection->remove(array('_id'=> new MongoId($this->id)));
	}
	public function insert(){
		$query = array('id_loaicongvan' => $this->id_loaicongvan ? new MongoId($this->id_loaicongvan) : '',
						'id_donvisoanthao' => $this->id_donvisoanthao ? new MongoId($this->id_donvisoanthao) : '',
						'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
						'trichyeu' => $this->trichyeu,
						'socongvan' => $this->socongvan,
						'sothutu' => $this->sothutu,
						'ngayky' => $this->ngayky,
						'ngaydiden' => $this->ngaydiden,
						'thoihanbaocao' => $this->thoihanbaocao,
						'canbobaocao' => $this->canbobaocao,
						'dabaocao' => $this->dabaocao,
						'cacvanbancolienquan' => $this->cacvanbancolienquan,
						'ghichu' => $this->ghichu,
						'dinhkem' => $this->dinhkem,
						'date_post' => new MongoDate(),
						'id_user' => new MongoId($this->id_user),
						'public' => $this->public,
						'emaillist' => $this->emaillist);
		return $this->_collection->insert($query);
	}

	public function edit(){
		$condition = array('_id' => new MongoId($this->id));
		$query = array('$set' => array('id_loaicongvan' => $this->id_loaicongvan ? new MongoId($this->id_loaicongvan) : '',
						'id_donvisoanthao' => $this->id_donvisoanthao ? new MongoId($this->id_donvisoanthao) : '',
						'id_linhvuc' => $this->id_linhvuc ? new MongoId($this->id_linhvuc) : '',
						'trichyeu' => $this->trichyeu,
						'socongvan' => $this->socongvan,
						'sothutu' => $this->sothutu,
						'ngayky' => $this->ngayky,
						'ngaydiden' => $this->ngaydiden,
						'thoihanbaocao' => $this->thoihanbaocao,
						'canbobaocao' => $this->canbobaocao,
						'dabaocao' => $this->dabaocao,
						'cacvanbancolienquan' => $this->cacvanbancolienquan,
						'ghichu' => $this->ghichu,
						'dinhkem' => $this->dinhkem,
						'public' => $this->public,
						'emaillist' => $this->emaillist));
		return $this->_collection->update($condition, $query);
	}
	public function count_all(){
		return $this->_collection->find()->count();
	}
	public function get_totalFilter($condition){
		return $this->_collection->find($condition)->sort(array('date_post'=>-1))->count();
	}

	public function get_list_to_position_condition($condition, $position, $limit){
		return $this->_collection->find($condition)->skip($position)->limit($limit)->sort(array('ngaydiden'=>-1, 'sothutu' => -1));
	}

	public function check_emaillist($id_emailaccount){
		$query = array('_id' => new MongoId($this->id), 'emaillist.id_emailaccount' => new MongoId($id_emailaccount));
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true; else return false;
	}

	public function check_email($id_emailaccount){
		$query = array('emaillist.id_emailaccount' => new MongoId($id_emailaccount));
		$fields = array('_id'=> true);
		$result = $this->_collection->findOne($query, $fields);
		if($result['_id']) return true; else return false;
	}

	public function push_emaillist(){
		$query = array('$push' => array('emaillist' => $this->emaillist));
		$condition = array('_id' => new MongoId($this->id));
		return $this->_collection->update($condition, $query);
	}

	public function set_read($id_emailaccount){
		//$query = array('$set' => array('emaillist.$.view' => 1));
		$query = array('$inc' => array('emaillist.$.view' => 1));
		$condition = array('_id' => new MongoId($this->id), 'emaillist.id_emailaccount' => new MongoId($id_emailaccount));
		return $this->_collection->update($condition, $query);	
	}

	public function get_public_list(){
		return $this->_collection->find(array('public'=>1))->limit(50)->sort(array('ngaydiden'=> -1));
	}

	public function get_one_public(){
		return $this->_collection->findOne(array('_id'=> new MongoId($this->id), 'public' => 1));
	}

	public function get_public_list_condition($condition){
		return $this->_collection->find($condition)->sort(array('ngaydiden'=> -1));
	}

	public function set_dabaocao(){
		$query = array('$set' => array('dabaocao' => intval($this->dabaocao)));
		$condition = array('_id' => new MongoId($this->id));

		return $this->_collection->update($condition, $query);
	}
}
?>