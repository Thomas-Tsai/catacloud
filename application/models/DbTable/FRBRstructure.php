<?php

class Application_Model_DbTable_FRBRstructure extends Zend_Db_Table_Abstract
{

    protected $_name = 'frbrstructure';

    public function get_basic_tag (){
	$select = $this->select();
	$select->where('mandatory=1');
	return $this->fetchAll($select);
    }

    public function get_advanced_tag (){
	$select = $this->select();
	$select->order(array('mandatory DESC' ));
	return $this->fetchAll($select);
    }


    public function getStructure($id)
    {
	$id = (int)$id;
	$row = $this->fetchRow('id='. $id);
	if (!$row){
	    //throw new Exception("Could not find news $id");    
	    return false;
	}
	return array($row->tagfield, $row->tagsubfield);
    }

    public function getKeyId($tagfield, $tagsubfield){
	$select = $this->select();
	$select->where('tagfield = ?', $tagfield);
	$select->where('tagsubfield = ?', $tagsubfield);
	$row = $this->fetchRow($select);
	if (!$row){
	    //throw new Exception("Could not find news $id");    
	    return false;
	}
	return $row{'Id'};

    }




}

