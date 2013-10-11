<?php
require('Zend/Soap/AutoDiscover.php');
class ServiceController extends Zend_Controller_Action
{

    private $_URI = "http://192.168.99.128/service/index";
    private $_WSDL_URI = "http://192.168.99.128/service/index?wsdl";

    public function init()
    {
	/* Initialize action controller here */
	// disable layouts and renderers
	$this->getHelper('viewRenderer')->setNoRender(true);
	$this->_helper->layout->disableLayout();

    }

    public function indexAction()
    {
	if (isset($_GET['wsdl'])) {
	    $this->handleWSDL();
	} else {
	    $this->handleSOAP();
	}
//	    $this->handleSOAP();
    }
    private function handleWSDL() {

	$autodiscover = new Zend_Soap_AutoDiscover();
	$autodiscover->setClass('Application_Model_BiblioService')
	    ->setUri($this->_URI);
	$autodiscover->handle();
    }

    private function handleSOAP() {

	//$soap = new Zend_Soap_Server($this->_WSDL_URI);
	$soap = new Zend_Soap_Server(null, array('uri' => $this->_WSDL_URI));
	$soap->setClass('Application_Model_BiblioService');
	$soap->handle();
    }


}

