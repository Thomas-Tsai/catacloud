<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
	/* Initialize action controller here */
    }

    public function indexAction()
    {
	// action body
    }

    public function registAction()
    {
	// action body
	// receive data from form or direct form to view
	$form = new Application_Form_User();
	$form->submit->setLabel('註冊');
	$this->view->form = $form;

	// add data to user db
	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$name= $form->getValue('name');
		$password       = $form->getValue('password');

		$user = new Application_Model_DbTable_User();
		$user->addUser($name, sha1($password));
		$this->_helper->redirector('index');
	    } else {
		$form->populate($formData);
	    }
	}
    }

    public function loginAction()
    {
	// action body
	$form = new Application_Form_Login();
	$this->view->form = $form;
	$this->view->message = "";
	$formData = $this->getRequest()->getPost();
	if ($this->_request->isPost ()) {
	    if (!$form->isValid($formData)){
		$this->view->error = '請重新輸入';
		return;
		//$form->populate();
	    }
	    $f = new Zend_Filter_StripTags ();
	    $name = $f->filter ( $this->_request->getPost ( 'name' ) );
	    $password = $f->filter ( $this->_request->getPost ( 'password' ) );
	    $password = sha1($password);

	    if (empty ( $name )) {
		$this->view->error = '請輸入您的帳號';
	    } else {
		// user
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$userAdapter = new Zend_Auth_Adapter_DbTable ($dbAdapter);
		$userAdapter->setTableName ( 'user' );
		$userAdapter->setIdentityColumn ( 'name' );
		$userAdapter->setCredentialColumn ( 'password' );
		$userAdapter->setIdentity ( $name );
		$userAdapter->setCredential ( $password );

		// do the authentication
		$user_auth = Zend_Auth::getInstance ();
		$userresult = $user_auth->authenticate ( $userAdapter);
		if ($userresult->isValid ()) {
		    $data = $userAdapter->getResultRowObject ( null, 'password' );
		    $user_auth->getStorage ()->write ( $data );
		    $session = new Zend_Session_Namespace('cata');
		    $session->setExpirationSeconds(60*60*4);
		    $session->name = $user_auth->getIdentity()->name;
		    $session->uid = $user_auth->getIdentity()->id;
		    $session->lib = $user_auth->getIdentity()->lib;
		    // redirect
		    $action = $this->_getParam("action");
		    $controller = $this->_getParam("controller");
		    $this->_helper->redirector($action, $controller);
		} else {
		    Zend_Auth::getInstance()->clearIdentity ();
		    $session = new Zend_Session_Namespace('cata');
		    unset($session->adminname);
		    Zend_Session::namespaceUnset('cata');
		    $this->view->error = "登入失敗";
		    //echo "you are not admin of $host";    
		}
	    }
	}
    }

    public function logoutAction()
    {
	// action body
	Zend_Auth::getInstance()->clearIdentity ();
	$session = new Zend_Session_Namespace('cata');
	unset($session->name);
	Zend_Session::namespaceUnset('cata');
	$this->_helper->redirector('index', 'index');

    }

    public function removeAction()
    {
	// action body
    }


}









