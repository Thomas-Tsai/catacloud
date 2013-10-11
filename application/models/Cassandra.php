<?php
require_once('/opt/phpcassa/lib/autoload.php');

use phpcassa\Connection\ConnectionPool;
use phpcassa\ColumnFamily;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;


class Application_Model_Cassandra
{

    public function add($uuid, $key, $value)
    {
	$pool = new ConnectionPool('catacloud_dev', array('127.0.0.1'));
	$biblio = new ColumnFamily($pool, 'biblio');
	$biblio->insert($uuid, array("keye" => $key, "value" => $value));
    }

    public function get($uuid)
    {
	try {
	    $pool = new ConnectionPool('catacloud_dev', array('127.0.0.1'));
	    $biblio = new ColumnFamily($pool, 'biblio');
	    //$user = $users->get('user0');
	    //$name = $user["name"];
	    //echo "Fetched user $name\n";
	    $data = $biblio->get($uuid);
	    return $data;
	}
	catch (Exception $tx){
	    return false;
	    //error_log( print_r( $tx, true ));
	}
	return false;
    }

}

