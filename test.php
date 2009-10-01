<?php 
require_once 'Tag.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php 
/*****************************************************************************************************
 *****************************************************************************************************
 ************************************   T E S T A U S G A B E N   ************************************
 *****************************************************************************************************
 *****************************************************************************************************/

//var_dump(get_html_translation_table(HTML_ENTITIES));

$br = Tag::createTag('br');
$hr = Tag::createTag('hr');


$preTag = Tag::createTag('pre');
echo $preTag->setContent("========================\n==== Ein <form>-Tag ====\n========================\n");

$items_test = array(
	'tickets_01' => 'Single-Ticket',
	'tickets_02' => 'Group-Ticket',
	'tickets_03' => array(
		'value' => 'Family-Ticket',
		'attributes' => array(
			'checked' => true,
			'onclick' => array(
				'value'   => "alert('Hallo Du Da! Du hast mich angeklickt.');", 
				'options' => array(
					'addSlashes' => false
				)
			)
		)
	)
);
$preTag->setContent(var_export($items_test, 1));
echo $preTag;

foreach(Tag::createChoiceTag($items_test, 'tickets', 'checkbox') as $item) {
	$itemContent.=$item;
}
$itemContent.= Tag::createInputTag('submit', 'submit', 'Submit');
$formTag3 = Tag::createFormTag(basename(__FILE__), $itemContent);
echo $formTag3;

echo $hr;
echo '<h2>POST</h2>';
echo $preTag->setContent(var_export($_POST,1));
echo $hr;


/*
$for = 'diesunddas';
$content =' Nase';
$label = Tag::createLabelTag($for, $content);
$input = Tag::createInputTag('checkbox', 'Name', 'nase', array('id'=>$for, 'class'=>'nix'));
echo $br.$hr.'Checkbox'.$br;
echo $label . $input;
echo $hr;


$f = Tag::createLabeledInputTag('Super-Label', 'checkbox', 'createLabeledInputTag', 'createLabeledInputTag-Value', $inputAttributes=array(), $labelAttributes=array());
echo $f;
echo $hr;
echo $preTag->setContent($f);
echo $hr;
*/


/*
$a = Tag::createTag('a');
$a->addAttribute(new Attribute('href', 'http://www.saltation.de', $a->getName()))
  ->addAttribute(new Attribute('shape', 'rect', $a->getName()))
  ->addAttribute(new Attribute('target', '_blank', $a->getName()))
  ->setContent($hr.'Blähbauch'.$hr);
echo $br.$a;
var_dump($a->display());

$a->setHtmlentities(false);
echo $br.$a;
var_dump($a->display());


$preTag = Tag::createTag('pre');
$data = array(
	array('name'=>'width', 'value'=>'10'),
	array('name'=>'style', 'value'=>'color:#333')
);
$preTag->addAttributes(AttributeFactory::createAttributes($preTag->getName(), $data));




$preTag->setContent("=======================\n= Ein anderes <a>-Tag =\n=======================\n");
echo $br.$br.$preTag.$br.$br;



$aTag = Tag::createTag('a');
$aTag->setContent('Me, Myself & I')
     ->addAttribute(AttributeFactory::createAttribute('href', 'http://www.timo-strotmann.de', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('shape', 'circle', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('target', '_blank', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('style', 'color:black; text-decoration:none;', $aTag->getName()));
echo $aTag;
var_dump($aTag->display());

$aTag->setHtmlentities(false);
echo $br.$aTag;
var_dump($aTag->display());



$attributes = array(
	array('name'=>'target', 'value'=>'_blank'),
	array('style', 'color:#333')
);
$a2Tag = Tag::createATag('http://www.heise.de', 'Heise', $attributes);
echo $a2Tag;
var_dump($a2Tag->display());
echo $hr;






$preTag->setContent("========================\n==== Ein <form>-Tag ====\n========================\n");
echo $br.$br.$preTag.$br.$br;

Tag::setPrefixId('dummy');

$textareaTag = Tag::createTextareaTag('textfeld', $content='', array(array('name'=>'cols', 'value'=>'30')));
            
$inputTag1 = Tag::createTag('input');
$inputTag1->addAttribute(AttributeFactory::createAttribute('type', 'button', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('name', 'Text 1', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('value', 'Text 1 anzeigen', $inputTag1->getName()))
          ->addAttribute(AttributeFactory::createAttribute('onclick', "this.form.dummy_textfeld.value='Text 1 und rückwärts seltsam geschrieben ich bin.'", $inputTag1->getName(), array('addSlashes'=>false)));

$inputTag2Data = array(
	array('name'=>'type', 'value'=>'button'),
	array('name'=>'name', 'value'=>'Text 2'),
	array('name'=>'value', 'value'=>'Text 2 anzeigen'),
	array('name'=>'onclick', 'value'=>"this.form.dummy_textfeld.value='Ich bin Text 2 - ganz normal'", 'options'=>array('addSlashes'=>false)),
);
$inputTag2 = Tag::createTag('input');
$inputTag2->addAttributes(AttributeFactory::createAttributes($inputTag2->getName(), $inputTag2Data));

$inputTag3Attr = array(
	array(
		'onclick', 
		"this.form.dummy_textfeld.value='Ich bin Text 2 - und ganz langweilig!'", 
		array('addSlashes'=>false)
	)
);
$inputTag3 = Tag::createInputTag('button', 'Text 3', 'Text 3 anzeigen', $inputTag3Attr);

          
$pTag = Tag::createTag('p');
$pTag->setHtmlentities(false)
		 ->setContent($textareaTag.$inputTag1.$inputTag2.$inputTag3);
		 
		 
$fielset = Tag::createFieldsetTag($pTag, 'I am Legend', $fieldsetAttributes=array(), $legendAttributes=array());
$formTag2 = Tag::createFormTag(basename(__FILE__), $fielset);


echo $formTag2;
var_dump($formTag2->display());
echo $hr;
*/



$itemObj2 = Tag::createTag('div');
$itemObj2->addAttributes(AttributeFactory::createAttributes($itemObj2->getName(), array('title'=>'Ich bin ein Title')));
$itemObj2->setContent('Spezial, mit \'title\'-Attirbute');
$itemObj = Tag::createTag('li');
$itemObj->addAttributes(AttributeFactory::createAttributes($itemObj->getName(), array('class'=>'special', 'style'=>'color:red;')));
$itemObj->setContent($itemObj2);
$itemObj->setHtmlentities(false);

$listItems = array(
	'Item 1' => array('class'=>'firstItem'),
	'Item 2' => array('class'=>'secondItem'),
	'Item 3' => array('class'=>'thirdItem'),
	$itemObj,
	'Item A', 'Item B', 'Item C',
);

//$listAttritbutes = array('type'=>'I', 'start'=>5, 'compact'=>'compact');
$listAttritbutes = array('type'=>'I', 'start'=>5, 'compact'=>true);
$list = Tag::createListTag($listItems, 'ol', $listAttritbutes);
echo '<h2>LIST</h2>';
echo $list;
echo $hr;


$listItems = array('Item A', 'Item B', 'Item C');
$list = Tag::createListTag($listItems);
echo $list;

?>

</body>
</html>