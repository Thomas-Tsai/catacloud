<?php

class Application_Model_BiblioService
{
    private function doAuthenticate() {
	if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {

	    if ($_SERVER['PHP_AUTH_USER'] == "xxx" && $_SERVER['PHP_AUTH_PW'] == "123")
		return true;
	    else
		return false;
	}
    }


    public function ListBiblio($type = '', $limit = ''){
	if (!$this->doAuthenticate())
	    return "Invalid username or password";
	$metadata = new Application_Model_DbTable_MetaData();
	$data = $metadata->listdata($type);
	return $data->toArray();
    }

    public function AddBiblio($type, $data){

	if (!$this->doAuthenticate())
	    return "Invalid username or password";
	$cas_UUID = uniqid(md5(rand()),true);

	// insert to alternative_cas_data
	$casdb = new Application_Model_DbTable_CasData();
	//trace name here, fix me
	$name ="test";
	$casdb->add($cas_UUID, $name, $data);

	// insert to metametadata
	$metadata = new Application_Model_DbTable_MetaData();
	$metadata->add('thomas', $cas_UUID, $type, '0', 'add');
	return $cas_UUID;
    }

    public function EditBiblio($ouuid, $type, $data){

	if (!$this->doAuthenticate())
	    return "Invalid username or password";
	$cas_UUID = uniqid(md5(rand()),true);

	// insert to alternative_cas_data
	$casdb = new Application_Model_DbTable_CasData();
	//trace name here, fix me
	$name="test";
	$casdb->add($cas_UUID, $name, $data);

	// insert to metametadata
	$metadata = new Application_Model_DbTable_MetaData();
	$metadata->add('thomas', $cas_UUID, $type, '0', "update from $ouuid");
	return $cas_UUID;

    }

    public function DeleteBiblio($uuid, $type){
	if (!$this->doAuthenticate())
	    return "Invalid username or password";
	if ($uuid){
	    // insert to metametadata
	    $metadata = new Application_Model_DbTable_MetaData();
	    $metadata->add('thomas', $uuid, $type, '0', "delete");
	    return true;
	}


    }

    public function DetailBiblio($uuid){

	if (!$this->doAuthenticate())
	    return "Invalid username or password";
	// access database
	if($uuid){
	    $casdb = new Application_Model_DbTable_CasData();
	    $UUID_data = $casdb->get($uuid);
	    return $UUID_data;
	} else {
	    return "no uuid";    
	}

    }

}

