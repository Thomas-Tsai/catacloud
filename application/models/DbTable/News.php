<?php

class Application_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';
    public function getNews($id)
    {
	$id = (int)$id;
	$row = $this->fetchRow('id='. $id);
	if (!$row){
	    //throw new Exception("Could not find news $id");    
	    return false;
	}
	return $row->toArray();
    }
    public function addNews ($title, $data)
    {
	$data = array(
	    'title' => $title,
	    'data' => $data,
	);
	$this->insert($data);
    }
    public function updateNews ($id, $title, $data)
    {
	$data = array(
	    'title' => $title,
	    'data' => $data,
	);
	$this->update($data, 'id = '.(int)$id);
    }
    public function deleteNews ($id){
	$this->delete('id ='.(int)$id);
    }
}
