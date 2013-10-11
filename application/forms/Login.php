<?php

class Application_Form_Login extends Zend_Form
{

	public function init()
	{
		/* Form Elements & Other Definitions Here ... */
		$this->setName('login');

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('帳號')
			->addValidator('NotEmpty')
			->setRequired(true);

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('密碼')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setLabel('登入');

		$this->addElements(array($name, $password, $submit));

	}


}

