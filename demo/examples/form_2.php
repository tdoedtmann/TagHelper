<?php 
$br = Tag::createTag('br');
$hr = Tag::createTag('hr');

Tag::setPrefixId(basename(__FILE__, '.php'));

$textareaTag = Tag::createTextareaTag('textfeld', $content='', array('cols'=>'30'));
            
$inputTag1 = Tag::createTag('input');
$inputTag1->addAttribute(AttributeFactory::createAttribute('type', 'button', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('name', 'Text 1', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('value', 'Text 1 anzeigen', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('onclick', "this.form.".Tag::getPrefixId()."_textfeld.value='Text 1 und rückwärts seltsam geschrieben ich bin.'", $inputTag1->getName(), array('addSlashes'=>false)));

$inputTag2Data = array(
	'type'    => 'button',
	'name'    => 'Text 2',
	'value'   => 'Text 2 anzeigen',
	'onclick' => array(
		'value'   => "this.form.".Tag::getPrefixId()."_textfeld.value='Ich bin Text 2 - ganz normal'",
		'options' => array(
			'addSlashes' => false
		)
	),
);
$inputTag2 = Tag::createTag('input');
$inputTag2->addAttributes(AttributeFactory::createAttributes($inputTag2->getName(), $inputTag2Data));

$inputTag3Attr = array(
	'onclick' => array(
		'value'   => "alert('Hallo Du Da! Du hast mich angeklickt.');", 
		'options' => array(
			'addSlashes' => false
		)
	)
);
$inputTag3 = Tag::createInputTag('button', 'Text 3', 'Text 3 anzeigen', $inputTag3Attr);

$span1 = Tag::createTag('span');
$span1->addAttribute(AttributeFactory::createAttribute('style', 'float:left', $span1->getName()))
	->setHtmlentities(false)
	->setContent($textareaTag);

$span2 = Tag::createTag('span');
$span2->addAttribute(AttributeFactory::createAttribute('style', 'float:left', $span2->getName()))
	->setHtmlentities(false)
	->setContent($inputTag1.$br.$inputTag2.$br.$inputTag3);

$pTag = Tag::createTag('p');
$pTag->setHtmlentities(false)
		 ->setContent($span1 . $span2);

$brClear = Tag::createTag('br');
$brClear->addAttribute(AttributeFactory::createAttribute('clear', 'all', $brClear->getName()));

$submit = Tag::createInputTag('submit', 'submit', 'Submit');

$fieldsetContent = $pTag . $brClear . $submit;

$fielset = Tag::createFieldsetTag($fieldsetContent, 'I am Legend', $fieldsetAttributes=array(), $legendAttributes=array());
$formTag2 = Tag::createFormTag($_SERVER['PHP_SELF'], $fielset);

echo $formTag2;