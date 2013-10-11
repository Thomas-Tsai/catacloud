<?php

class Application_Model_DbTable_MetaData extends Zend_Db_Table_Abstract
{

    protected $_name = 'metametadata';

    public function add($user, $UUID, $title, $author = '', $isbn = '', $type, $lib, $action){
	$data = array(
		    'user'  => $user,
		    'UUID'  => $UUID,
		    'title' => $title,
		    'author' => $author,
		    'isbn'   => $isbn,
		    'type'   => $type,
		    'lib' => $lib,
		    'action' => $action,
		);
	$this->insert($data);

    }
    public function get($uuid){
	$row = $this->fetchRow("`UUID`='$uuid'");
	if (!$row){
	    //throw new Exception("Could not find news $id");
	    return false;
	}
	return $row->toArray();
    }
    public function listdata($type){
	$select = $this->select();
	if ($type)
	    $select->where('type = ?', $type);
	$select->order(array('date DESC'));
	$row = $this->fetchAll($select);
	return $row;
    }
    public function populardata($limit = 5){
	$select = $this->select();
	$select->limit($limit);
	$select->order(array('active DESC'));
	$row = $this->fetchAll($select);
	return $row;
    }


    public function newdata($limit = 5){
	$select = $this->select();
	$select->where("`action` LIKE 'add'");
	$select->limit($limit);
	$select->order(array('date DESC'));
	$row = $this->fetchAll($select);
	return $row;
    }


    public function active($uuid){
	$this->update(array('active' => new Zend_Db_Expr('active + 1')), "`UUID` = '$uuid'");
    }

}

