<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setName('user');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('帳號')
            ->setRequired(true)
            ->addValidator('NotEmpty');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('密碼')
            ->setRequired(true)
            ->addValidator('StringLength', false, array(8))
            ->addValidator('NotEmpty');


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $name, $password, $submit));

        $this->setDecorators(array(
            'FormElements',
                array('HtmlTag', array('tag' => 'table')),
                    'Form',
                    ));
        $this->setElementDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tag' => 'td')),
                    array('Label', array('tag' => 'td')),
                    array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
                    ));



    }


}

