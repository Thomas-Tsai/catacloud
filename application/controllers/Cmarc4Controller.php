<?php

class Cmarc4Controller extends Zend_Controller_Action
{

    public function init()
    {
	/* Initialize action controller here */
    }

    public function preDispatch()
    {
	$auth = Zend_Auth::getInstance ();
	if (! $auth->hasIdentity ()) {
	    $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	    $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	    //echo "permission deny";
	    $this->_forward('login',
		    'user',
		    null, 
                    array(
                        "action"=>$action,
                        "controller"=>$controller,
                        )
		    );

	}
    }

    public function indexAction()
    {
	// action body
	/*
	   $marc21_table = new Application_Model_DbTable_Marc21structure();
	   $this->view->basic_tag = $marc21_table->get_basic_tag();
	   $this->view->advanced_tag = $marc21_table->get_advanced_tag();
	 */
	// list metametadata
	$entry = new Application_Model_DbTable_MetaData();
	$data = $entry->listdata('3');
	$this->view->data = $data;
    }

    public function addAction()
    {
	// action body

	// check edit style
	$edit_style = $this->_getParam('edit_style', 'basic');

	// render form
	if ($edit_style == "basic")
	    $form = new Application_Form_Cmarc4basic();
	else
	    $form = new Application_Form_Cmarc4adv();

	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$Cmarc4 = new Application_Model_DbTable_Cmarc4structure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if ($key != 'submit'){
			list ($field, $subfield) = $Cmarc4->getStructure($key);
			$value = $form->getValue($key);
			if (($field == '200') && ($subfield == 'a'))
			    $name = $value;
			if ($value) {
			    //echo " $key $field, $subfield, $value\n";
			    array_push( $data, "$field,$subfield,$value");
			}
		    }
		}
		$cas_UUID = uniqid(md5(rand()),true);

		// insert to alternative_cas_data
		$casdb = new Application_Model_DbTable_CasData();
		$casdb->add($cas_UUID, $name, implode(":",$data));
		$sent_data = $casdb->get($cas_UUID);

		// insert to metametadata
		$metadata = new Application_Model_DbTable_MetaData();
		$metadata->add('thomas', $cas_UUID, '3', '0', 'add');
		$this->_helper->redirector('index');
	    } else {
		$form->populate($formData);
	    }
	}
    }

    public function editAction()
    {
	// action body

	// render form
	$form = new Application_Form_Cmarc4adv();
	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$Cmarc4 = new Application_Model_DbTable_Cmarc4structure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if (($key != 'submit') && ($key != 'OLDUUID')){
			list ($field, $subfield) = $Cmarc4->getStructure($key);
			$value = $form->getValue($key);
			if (($field == '200') && ($subfield == 'a'))
			    $name = $value;
			if ($value) {
			    //echo " $key $field, $subfield, $value\n";
			    array_push( $data, "$field,$subfield,$value");
			}
		    }
		}
		$cas_UUID = uniqid(md5(rand()),true);

		// insert to alternative_cas_data
		$casdb = new Application_Model_DbTable_CasData();
		$casdb->add($cas_UUID, $name, implode(":",$data));

		// insert to metametadata
		$metadata = new Application_Model_DbTable_MetaData();
		$oUUID = $this->_getParam('UUID');
		$metadata->add('thomas', $cas_UUID, '3', '0', "update from $oUUID");
		$this->_helper->redirector('index');

	    } else {
		$form->populate($formData);
	    }
	} else {
	    $UUID = $this->_getParam('UUID');
	    if ($UUID){

		$casdb = new Application_Model_DbTable_CasData();
		$UUID_data = $casdb->get($UUID);
		$Cmarc4_value = explode(":", $UUID_data{"value"});

		foreach ($Cmarc4_value as $field){
		    list($tagfield, $tagsubfield, $value) = explode(",", $field);
		    $key = $Cmarc4->getKeyId($tagfield, $tagsubfield);
		    $Cmarc4_data{$key} = $value;
		}
		//$marc21_data{'1'} = "test";
		//$marc21_data{'OLDUUID'} = $UUID;
		//$form->OLDUUID->setValue($UUID);
		$form->populate($Cmarc4_data);
	    }

	}
    }

    public function deleteAction()
    {
	// action body
	$UUID = $this->_getParam('UUID');
	if ($UUID){
	    // insert to metametadata
	    $metadata = new Application_Model_DbTable_MetaData();
	    $metadata->add('thomas', $UUID, '3', '0', "delete");
	    $this->_helper->redirector('index');
	}

    }

    public function detailAction()
    {
	// action body
	$UUID = $this->getParam('UUID');
	//echo $UUID;

	// access database
	$casdb = new Application_Model_DbTable_CasData();
	$UUID_data = $casdb->get($UUID);
	$this->view->detail = $UUID_data;
	$this->view->UUID = $UUID;
    }

}











