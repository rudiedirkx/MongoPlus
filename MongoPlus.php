<?php

class MongoPlus extends Mongo {
	public function __get( $name ) {
		return $this->selectDB($name);
	}
	public function selectDB( $name ) {
		return new MongoDBPlus($this, $name);
	}
}

class MongoDBPlus extends MongoDB {
	public $_mongo;
//	public $_mongo_id;
	public function __construct( MongoPlus $mongo, $name ) {
		$this->_mongo = $mongo;
//		$this->_mongo_id = md5(microtime(1));
//		$this->ref_mongo($mongo);
		parent::__construct($mongo, $name);
	}
/*	public function ref_mongo( MongoPlus $mongo = null ) {
		static $mongos;
		if ( $mongo ) {
			$mongos[$this->_mongo_id] = $mongo;
		}
		return $mongos[$this->_mongo_id];
	}*/
	public function __get( $name ) {
		return $this->selectCollection($name);
	}
	public function selectCollection( $name ) {
		return new MongoCollectionPlus($this, $name);
	}
}

class MongoCollectionPlus extends MongoCollection {
	public function find( $query = array(), $fields = array() ) {
		return new MongoCursorPlus($this->db->_mongo, (string)$this, $query, $fields, $this);
	}
}

class MongoCursorPlus extends MongoCursor {
	public $collection;
	public function __construct( $mongo, $ns, $query, $fields, $collection ) {
		$this->_collection = $collection;
		parent::__construct($mongo, $ns, $query, $fields);
	}
	public function update( Closure $callback, $options = array() ) {
		isset($options['multiple']) or $options['multiple'] = false;
		$updated = array();
		foreach ( $this AS $i => $doc ) {
			$update = $callback($doc);
			$id = $doc['_id'];
//			$id = $id->{'$id'}; // invalid -- _id must be typeof MongoId
//			$id = new MongoId($id->{'$id'}); // valid -- but unnecessary, because $doc['_id'] is just that
			$updated[$i] = $this->_collection->update(array('_id' => $id), $update, $options);
		}
		return $updated;
	}
}


