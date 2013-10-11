<?php

class Application_Model_DbTable_Library extends Zend_Db_Table_Abstract
{

    protected $_name = 'library';
    public function getLibrary($id)
    {
	$id = (int)$id;
	$row = $this->fetchRow('id='. $id);
	if (!$row){
	    //throw new Exception("Could not find library $id");    
	    return false;
	}
	return $row->toArray();
    }

    public function active($id){
	$this->update(array('active' => new Zend_Db_Expr('active + 1')), 'id = '.(int)$id);
    }


}

