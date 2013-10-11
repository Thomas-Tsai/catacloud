<?php

class FrbrController extends Zend_Controller_Action
{

    public function init()
    {
	/* Initialize action controller here */
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
	$entry = new Application_Model_DbTable_MetaData();
	$data = $entry->listdata('1');
	$this->view->data = $data;
    }

    public function addAction()
    {
	// action body

	// check edit style
	$edit_style = $this->_getParam('edit_style', 'basic');

	// render form
	if ($edit_style == "basic")
	    $form = new Application_Form_FRBRbasic();
	else
	    $form = new Application_Form_FRBRadv();

	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$FRBR = new Application_Model_DbTable_FRBRstructure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if ($key != 'submit'){
			list ($field, $subfield) = $FRBR->getStructure($key);
			$value = $form->getValue($key);
			if (($field == 'work') && ($subfield == 'title_work'))
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
		$metadata->add('thomas', $cas_UUID, '1', '0', 'add');
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
	$form = new Application_Form_FRBRadv();
	$this->view->form = $form;
	$this->view->message = "";

	// add data to user db
	$FRBR = new Application_Model_DbTable_FRBRstructure();
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data = array();
		foreach ( $form->getElements() as $element){
		    $key = $element->getId(); // tagfield and tagsubfield
		    if (($key != 'submit') && ($key != 'OLDUUID')){
			list ($field, $subfield) = $FRBR->getStructure($key);
			$value = $form->getValue($key);
			if (($field == 'work') && ($subfield == 'title_work'))
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
		$metadata->add('thomas', $cas_UUID, '1', '0', "update from $oUUID");
		$this->_helper->redirector('index');

	    } else {
		$form->populate($formData);
	    }
	} else {
	    $UUID = $this->_getParam('UUID');
	    if ($UUID){

		$casdb = new Application_Model_DbTable_CasData();
		$UUID_data = $casdb->get($UUID);
		$FRBR_value = explode(":", $UUID_data{"value"});

		foreach ($FRBR_value as $field){
		    list($tagfield, $tagsubfield, $value) = explode(",", $field);
		    $key = $FRBR->getKeyId($tagfield, $tagsubfield);
		    $FRBR_data{$key} = $value;
		}
		$form->populate($FRBR_data);
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
	    $metadata->add('thomas', $UUID, '1', '0', "delete");
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









