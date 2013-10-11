<?php

class Application_Form_News extends Zend_Form
{

    public function init()
    {
	$this->setName('news');
	$id = new Zend_Form_Element_Hidden('id');
	$id->addFilter('Int');

	$title = new Zend_Form_Element_Textarea('title');
	$title->setLabel('標題')
	    ->setRequired(true)
	    ->setAttrib('id', 'elm1')
	    ->addValidator('NotEmpty');

	$data = new Zend_Form_Element_Textarea('data');
	$data->setLabel('內容')
	    ->setRequired(true)
	    ->setAttrib('id', 'elm1')
	    ->addValidator('NotEmpty');


	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton');

	$this->addElements(array($id, $title, $data, $submit));

    }

}

