<?php
$itemObj2 = Tag::createTag('div');
$itemObj2->addAttributes(AttributeFactory::createAttributes($itemObj2->getName(), array('title'=>'Ich bin ein Title')))
	->setContent('Spezial, mit \'title\'-Attirbute');

$listItem = Tag::createTag('li');
$listItem->addAttributes(AttributeFactory::createAttributes($listItem->getName(), array('class'=>'special', 'style'=>'color:red;')))
	->setHtmlentities(false)
	->setContent($itemObj2);
	
$listItems = array(
	'Item 1' => array('class'=>'firstItem'),
	'Item 2' => array('class'=>'secondItem'),
	'Item 3' => array('class'=>'thirdItem'),
	$listItem,
	'Item A', 'Item B', 'Item C',
);

echo Tag::createListTag($listItems, 'ol', array('type'=>'I', 'start'=>5, 'compact'=>true));
