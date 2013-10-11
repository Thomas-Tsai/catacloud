<?php

class Application_Model_Biblio
{
    public function NameOfLibrary($library){
	$db_library = new Application_Model_DbTable_Library();
	$library_str =  $db_library->getLibrary($library);
	//print_r($type_str);
	return $library_str['name'];
    }

    public function NameOfUser($user){
	$db_user = new Application_Model_DbTable_User();
	$user_str =  $db_user->getUser($user);
	//print_r($type_str);
	return $user_str['name'];
    }


    public function NameOfType($type){
	$db_type = new Application_Model_DbTable_Type();
	$type_str =  $db_type->getType($type);
	//print_r($type_str);
	return $type_str['name'];
    }

    public function LinkOfUUID($uuid){
	$meta = new Application_Model_DbTable_Metadata();
	$uuid_datas = $meta->get($uuid);
	$uuid_data = $uuid_datas;
	$type_str = $this->NameOfType($uuid_data['type']);
	$mesg = "/".$type_str."/detail/UUID/".$uuid_data['UUID'];
	return $mesg ;

    }

    public function NameOfAction($action, $type){
	if (!preg_match("/update from (.*)/", $action, $match_uuid))
	    return $action;
	else{
	    $type_str = $this->NameOfType($type);
	    $short_update_mesg = "update from <a href=\"".$this->LinkOfUUID($match_uuid[1])."\">...</a>";
	    return $short_update_mesg ;
	}
    }

    public function LogMeta($uuid){
	$meta = new Application_Model_DbTable_MetaData();
	$meta->active($uuid);
	
    }
    public function LogUserLib($uid, $lib){
	$user = new Application_Model_DbTable_User();
	$user->active($uid);
	$library = new Application_Model_DbTable_Library();
	$library->active($lib);
    }
}

