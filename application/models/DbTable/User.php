<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';

    public function getUser($id)
    {  
        $id = (int)$id;
        $row = $this->fetchRow('id='. $id);
        if (!$row){
            //throw new Exception("Could not find superuser $id");    
            return false;
        }
        return $row->toArray();
    }
    public function addUser ($name, $password)
    {  
        $data = array(
            'name' => $name,
            'password' => $password,
        );
        $this->insert($data);
    }
    public function updateUser ($id, $name, $password)
    {  
        $data = array(
            'name' => $name,
            'password' => $password,
        );
        $this->update($data, 'id = '.(int)$id);
    }
    public function deleteUser ($id){
        $this->delete('id ='.(int)$id);
    }

    public function updatePassword ($id, $password)
    {  
        $data = array(
            'password'  => $password,
            );
        $this->update($data, 'id = '.(int)$id);
    }

    public function active($id){
	$this->update(array('active' => new Zend_Db_Expr('active + 1')), 'id = '.(int)$id);
    }

}

