<?php

class Application_Form_Cmarc4basic extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

	$this->setName('basictag');
	$cmarc4_table = new Application_Model_DbTable_Cmarc4structure();
	$basic_tags = $cmarc4_table->get_basic_tag();
	foreach ($basic_tags as $tag){
	    $field = $tag->Id;
	    $field_desc = "$tag->tagfield $tag->tagsubfield $tag->liblibrarian";
	    $tag_elementary = new Zend_Form_Element_Text($field);
	    $tag_elementary->setRequired(true)
		->setLabel("$field_desc")
		->addValidator('NotEmpty');

	    $this->addElement($tag_elementary);

	}
	//$test_elementary = new Zend_Form_Element_Text("abc");
	//$test_elementary->setLabel("測試");
	//$this->addElement($test_elementary);
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElement($submit);

    }


}

