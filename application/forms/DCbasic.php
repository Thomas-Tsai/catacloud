<?php

class Application_Form_DCbasic extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	$this->setName('basictag');
	$dublincore_table = new Application_Model_DbTable_DCstructure();
	$basic_tags = $dublincore_table->get_basic_tag();
	foreach ($basic_tags as $tag){
	    $field = $tag->Id;
	    $field_desc = "$tag->tagfield $tag->tagsubfield $tag->liblibrarian";
	    $tag_elementary = new Zend_Form_Element_Text($field);
	    $tag_elementary->setLabel("$field_desc");
		//->setRequired(true);
		//->addValidator('NotEmpty');

	    $this->addElement($tag_elementary);

	}
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElement($submit);

    }


}

