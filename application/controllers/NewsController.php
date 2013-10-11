<?php

class NewsController extends Zend_Controller_Action
{


    public function init()
    {
	/* Initialize action controller here */
    }

    public function preDispatch()
    {
	$auth = Zend_Auth::getInstance ();
	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	if ($action != "index"){
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
    }

    public function indexAction()
    {
	// action body
	$init_count=10;
	$page = $this->_getParam('page', 0);
	$news = new Application_Model_DbTable_News();
	$select = $news->select();
	$select->order(array('date DESC'));
	$news_data = $news->fetchAll($select);
	$paginator=Zend_Paginator::factory($news_data);
	$paginator->setCurrentPageNumber($page);
	$paginator->setItemCountPerPage($init_count);
	$this->view->news = $paginator;
    }

    public function addAction()
    {
	// action body
	$form = new Application_Form_News();
	$form->submit->setLabel('新增');
	$this->view->form = $form;

	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$data	= $form->getValue('data');
		$title	= $form->getValue('title');
		$news = new Application_Model_DbTable_News();
		$news->addNews($title, $data);
		$this->_helper->redirector('index');
	    } else {
		$form->populate($formData);
	    }
	}

    }

    public function editAction()
    {
	// action body
	$form = new Application_Form_News();
	$form->submit->setLabel('儲存');
	$this->view->form = $form;

	if ($this->getRequest()->isPost()){
	    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)){
		$id	= (int)$form->getValue('id');
		$data	= $form->getValue('data');
		$title	= $form->getValue('title');

		$news = new Application_Model_DbTable_News();
		$news->updateNews($id, $title, $data);
		$this->_helper->redirector('index');
	    } else {
		$form->populate($formData);
	    }
	} else {
	    $id = $this->_getParam('id', 0);
	    if ($id > 0){
		$news = new Application_Model_DbTable_News();
		$form->populate($news->getNews($id));
	    }
	}

    }

    public function deleteAction()
    {
	// action body
	if ($this->getRequest()->isPost()){
	    $del = $this->getRequest()->getPost('del');
	    if ($del == "Yes"){
		$id = $this->getRequest()->getPost('id');
		$news = new Application_Model_DbTable_News();
		$news->deleteNews($id);	
	    }
	    $this->_helper->redirector('index');
	} else {
	    $id = $this->_getParam('id', 0);
	    $news = new Application_Model_DbTable_News();
	    $this->view->news = $news->getNews($id);
	}

    }

}




