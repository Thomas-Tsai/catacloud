<?php

class Application_Form_Cmarc3adv extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

	$this->setName('advancedtag');
	$cmarc3_table = new Application_Model_DbTable_Cmarc3structure();
	$basic_tags = $cmarc3_table->get_advanced_tag();
	foreach ($basic_tags as $tag){
	    $field = $tag->Id;
	    $field_desc = "$tag->tagfield $tag->tagsubfield $tag->liblibrarian";
	    $tag_elementary = new Zend_Form_Element_Text($field);
	    $tag_elementary->setLabel("$field_desc");
	    $this->addElement($tag_elementary);

	}
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElement($submit);

    }


}

