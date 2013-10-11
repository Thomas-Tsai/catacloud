<?php

class Application_Model_DbTable_CasData extends Zend_Db_Table_Abstract
{

    protected $_name = 'alternative_cas_data';

    public function add($uuid, $key, $value){
	$data = array(
		'UUID'  => $uuid,
		'key'   => $key,
		'value' => $value,
		);
	$this->insert($data);

	// insert to cassandra server
	$cassandra = new Application_Model_Cassandra();
	$cassandra->add($uuid, $key, $value);

    }
    public function get($uuid){


	$cassandra = new Application_Model_Cassandra();
	$cas_row = $cassandra->get($uuid);
	if ($cas_row != false)
	    return $cas_row;

	$row = $this->fetchRow("`UUID`='$uuid'");
	if (!$row){
	    //throw new Exception("Could not find news $id");
	    return false;
	}
	return $row->toArray();
    }
}

