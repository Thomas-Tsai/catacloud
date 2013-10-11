<?php

class Marc21Controller extends Zend_Controller_Action
{

    public function init()
    {
	/* Initialize action controller here */
	$bib = new Application_Model_Biblio();
	$session = new Zend_Session_Namespace('cata');
	$bib->LogUserLib($session->uid, $session->lib);
    }

    public function preDispatch()
    {
	$auth = Zend_Auth::getInstance ();
	if (! $auth->hasIdentity ()) {
	    //echo "permission deny";
	    $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	    $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
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
	// list metametadata
    	$init_count=10;
	$page = $this->_getParam('page', 0);
	$entry = new Application_Model_DbTable_MetaData();
	$metadata = $entry->listdata('4');
	//$this->view->data = $data;
	$paginator=Zend_Paginator::factory($metadata);
	$paginator->setCurrentPageNumber($page);
	$paginator->setItemCountPerPage($init_count);
	$this->view->data = $paginator;

    }

    public function addAction()
    {
	// action body

	// check edit style
	$edit_style = $this->_getParam('edit_style', 'basic');

	// render form
	if ($edit_style == "basic")
	    $form = new Application_Form_Marc21basic();
	else
	    $form = new Application_Form_Marc21adv();

	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$Marc21 = new Application_Model_DbTable_Marc21structure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if ($key != 'submit'){
			list ($field, $subfield) = $Marc21->getStructure($key);
			$value = $form->getValue($key);
			if (($field == '022') && ($subfield == 'a'))
			    $isbn = $value;
			if (($field == '020') && ($subfield == 'a'))
			    $isbn = $value;
			if (($field == '100') && ($subfield == 'a'))
			    $author = $value;
			if (($field == '245') && ($subfield == 'a'))
			    $title = $value;
			if ($value) {
			    //echo " $key $field, $subfield, $value\n";
			    array_push( $data, "$field,$subfield,$value");
			}
		    }
		}
		$cas_UUID = uniqid(md5(rand()),true);

		// insert to alternative_cas_data and cassandra server
		$casdb = new Application_Model_DbTable_CasData();
		$casdb->add($cas_UUID, $title, implode(":",$data));

		// insert to metametadata
		$session = new Zend_Session_Namespace('cata');
		$creator = $session->uid;
		$type = '4';
		$library = '0';
		$metadata = new Application_Model_DbTable_MetaData();
		$metadata->add($creator, $cas_UUID, $title, $author, $isbn, $type, $library, 'add');
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
	$form = new Application_Form_Marc21adv();
	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$Marc21 = new Application_Model_DbTable_Marc21structure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if (($key != 'submit') && ($key != 'OLDUUID')){
			list ($field, $subfield) = $Marc21->getStructure($key);
			$value = $form->getValue($key);
			if (($field == '245') && ($subfield == 'a'))
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
		$oUUID = $this->_getParam('UUID');
		$metadata->add('thomas', $cas_UUID, '4', '0', "update from $oUUID");
		$this->_helper->redirector('index');

	    } else {
		$form->populate($formData);
	    }
	} else {
	    $UUID = $this->_getParam('UUID');
	    if ($UUID){

		$casdb = new Application_Model_DbTable_CasData();
		$UUID_data = $casdb->get($UUID);
		$marc21_value = explode(":", $UUID_data{"value"});

		foreach ($marc21_value as $field){
		    list($tagfield, $tagsubfield, $value) = explode(",", $field);
		    $key = $Marc21->getKeyId($tagfield, $tagsubfield);
		    $marc21_data{$key} = $value;
		}
		//$marc21_data{'1'} = "test";
		//$marc21_data{'OLDUUID'} = $UUID;
		//$form->OLDUUID->setValue($UUID);
		$form->populate($marc21_data);
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
	    $metadata->add('thomas', $UUID, '4', '0', "delete");
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
	$bib = new Application_Model_Biblio();
	$bib->LogMeta($UUID);
    }


}









