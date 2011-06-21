<?php
$itemObj2 = Tag::div('Spezial, mit \'title\'-Attirbute');
$itemObj2->addAttributes(AttributeFactory::createAttributes($itemObj2->getName(), array('title'=>'Ich bin ein Title')));

$listItem = Tag::li($itemObj2, array('class'=>'special', 'style'=>'color:red;'))->setHtmlentities(FALSE);
  
$listItems = array(
  'Item 1' => array('class'=>'firstItem'),
  'Item 2' => array('class'=>'secondItem'),
  'Item 3' => array('class'=>'thirdItem'),
  $listItem,
  'Item A', 'Item B', 'Item C',
);

echo Tag::ol($listItems, array('type'=>'I', 'start'=>5, 'compact'=>TRUE));
