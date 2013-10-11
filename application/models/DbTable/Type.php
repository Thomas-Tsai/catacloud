<?php

class Application_Model_DbTable_Type extends Zend_Db_Table_Abstract
{

    protected $_name = 'type';
    public function getType($id)
    {
	$id = (int)$id;
	$row = $this->fetchRow('id='. $id);
	if (!$row){
	    //throw new Exception("Could not find type $id");    
	    return false;
	}
	return $row->toArray();
    }

}

